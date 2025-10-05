<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Address;
use App\Models\ContactNumber;
use App\Models\WarehouseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
   public function index()
{
    $warehouses = Warehouse::with([
        'address',
        'sections.subsections.stocks.product.type'
    ])->get();

    foreach ($warehouses as $warehouse) {
        $totals = [];
        $grandTotal = 0;

        foreach ($warehouse->sections as $s) {
            foreach ($s->subsections as $sub) {
                foreach ($sub->stocks as $stock) {
                    $qty = $stock->Quantity;
                    $typeName = $stock->product->type->Type_Name ?? 'Other';
                    $totals[$typeName] = ($totals[$typeName] ?? 0) + $qty;
                    $grandTotal += $qty;
                }
            }
        }

        // assign colors
        $colorMap = [
            'Electronics' => '#4e73df',
            'Furniture'   => '#1cc88a',
            'Groceries'   => '#36b9cc',
            'Clothing'    => '#f6c23e',
            'Other'       => '#e74a3b',
        ];

        $warehouse->categories = [];
        foreach ($totals as $type => $qty) {
            $pct = $grandTotal > 0 ? round(($qty / $grandTotal) * 100, 1) : 0;
            $warehouse->categories[] = [
                'name'    => $type,
                'percent' => $pct,
                'color'   => $colorMap[$type] ?? '#999'
            ];
        }
    }

    return view('warehouse.index', compact('warehouses'));
}


    public function overview($id)
    {
        return view('warehouse.show');
    }

    public function create()
    {
        return view('warehouse.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:100',
            'Max_Capacity' => 'required|integer|min:1',
            'operational_status' => 'required|in:active,maintenance,closed',
            'street_address' => 'required|string|max:100',
            'city' => 'required|string|max:50',
            'state_province' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:50',
            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'required_with:contacts.*.contact_value|in:phone,mobile,fax,email',
            'contacts.*.contact_value' => 'required_with:contacts.*.contact_value|string|max:50',
            'sections' => 'required|array|min:1',
            'sections.*.section_name' => 'required|string|max:50',
            'sections.*.s_Capacity' => 'required|integer|min:1',

            'sections.*.temperature_zone' => 'required|in:ambient,chilled,frozen,controlled',
        ]);
        $totalSectionCapacity = collect($validatedData['sections'])->sum('s_Capacity');

        if ($totalSectionCapacity > $validatedData['Max_Capacity']) {
            return redirect()->back()->withInput()->withErrors([
                'sections' => 'Total section capacity (' . $totalSectionCapacity . ') exceeds warehouse capacity (' . $validatedData['Max_Capacity'] . ').'
            ]);
        }
        foreach ($validatedData['sections'] as $index => $section) {
            $totalSubCap = collect($section['subsections'] ?? [])->sum('subsection_capacity');
            if ($totalSubCap > $section['s_Capacity']) {
                return back()->withInput()->withErrors([
                    "sections.$index.subsections" => "Total subsection capacity ($totalSubCap) exceeds section capacity ({$section['s_Capacity']})."
                ]);
            }
        }



        DB::beginTransaction();

        try {
            $address = Address::create([
                'street_address' => $validatedData['street_address'],
                'city' => $validatedData['city'],
                'state_province' => $validatedData['state_province'] ?? null,
                'postal_code' => $validatedData['postal_code'] ?? null,
                'country' => $validatedData['country'],
            ]);

            $warehouse = Warehouse::create([
                'address_ID' => $address->address_ID,
                'Name' => $validatedData['Name'],
                'Max_Capacity' => $validatedData['Max_Capacity'],
                'operational_status' => $validatedData['operational_status'],
            ]);

            if (isset($validatedData['contacts']) && is_array($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $contactData) {
                    if (!empty($contactData['contact_value'])) {
                        $contact = ContactNumber::create([
                            'contact_type' => $contactData['contact_type'],
                            'contact_value' => $contactData['contact_value'],
                            'is_primary' => false,
                        ]);
                        $warehouse->contacts()->attach($contact->contact_ID);
                    }
                }
            }

            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                foreach ($validatedData['sections'] as $sectionData) {
                    $section = WarehouseSection::create([
                        's_Capacity' => $sectionData['s_Capacity'],
                        'section_name' => $sectionData['section_name'],
                        'temperature_zone' => $sectionData['temperature_zone'],
                    ]);
                    $warehouse->sections()->attach($section->section_ID);
                }
            }

            DB::commit();

            return redirect()->route('warehouses.index')->with('success', 'Warehouse added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to add warehouse: ' . $e->getMessage()]);
        }
    }

    public function edit(Warehouse $warehouse)
    {
        $warehouse->load('address', 'contacts', 'sections');
        return view('warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:100',
            'Max_Capacity' => 'required|integer|min:1',
            'operational_status' => 'required|in:active,maintenance,closed',
            'street_address' => 'required|string|max:100',
            'city' => 'required|string|max:50',
            'state_province' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:50',
            'contacts' => 'nullable|array',
            'contacts.*.contact_ID' => 'nullable|integer|exists:contact_numbers,contact_ID',
            'contacts.*.contact_type' => 'required_with:contacts.*.contact_value|in:phone,mobile,fax,email',
            'contacts.*.contact_value' => 'required_with:contacts.*.contact_type|string|max:50',
            'new_contacts' => 'nullable|array',
            'new_contacts.*.contact_type' => 'required_with:new_contacts.*.contact_value|in:phone,mobile,fax,email',
            'new_contacts.*.contact_value' => 'required_with:new_contacts.*.contact_value|string|max:50',
            'sections' => 'required|array|min:1',
            'sections.*.section_ID' => 'nullable|integer|exists:Warehouse_Sections,section_ID',
            'sections.*.section_name' => 'required|string|max:50',
            'sections.*.s_Capacity' => 'required|integer|min:1',
            'sections.*.temperature_zone' => 'required|in:ambient,chilled,frozen,controlled',
        ]);
        $totalSectionCapacity = collect($validatedData['sections'])->sum('s_Capacity');

        if ($totalSectionCapacity > $validatedData['Max_Capacity']) {
            return redirect()->back()->withInput()->withErrors([
                'sections' => 'Total section capacity (' . $totalSectionCapacity . ') exceeds warehouse capacity (' . $validatedData['Max_Capacity'] . ').'
            ]);
        }
        foreach ($validatedData['sections'] as $index => $section) {
            $totalSubCap = collect($section['subsections'] ?? [])->sum('subsection_capacity');
            if ($totalSubCap > $section['s_Capacity']) {
                return back()->withInput()->withErrors([
                    "sections.$index.subsections" => "Total subsection capacity ($totalSubCap) exceeds section capacity ({$section['s_Capacity']})."
                ]);
            }
        }



        DB::beginTransaction();

        try {
            $warehouse->update([
                'Name' => $validatedData['Name'],
                'Max_Capacity' => $validatedData['Max_Capacity'],
                'operational_status' => $validatedData['operational_status'],
            ]);

            if ($warehouse->address) {
                $warehouse->address->update([
                    'street_address' => $validatedData['street_address'],
                    'city' => $validatedData['city'],
                    'state_province' => $validatedData['state_province'] ?? null,
                    'postal_code' => $validatedData['postal_code'] ?? null,
                    'country' => $validatedData['country'],
                ]);
            }

            $contactIdsForSync = [];
            if (!empty($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $contactData) {
                    if (isset($contactData['contact_ID']) && !empty($contactData['contact_value'])) {
                        $contact = ContactNumber::find($contactData['contact_ID']);
                        if ($contact) {
                            $contact->update([
                                'contact_type' => $contactData['contact_type'],
                                'contact_value' => $contactData['contact_value'],
                            ]);
                            $contactIdsForSync[] = $contact->contact_ID;
                        }
                    } elseif (!empty($contactData['contact_value'])) {
                        $newContact = ContactNumber::create([
                            'contact_type' => $contactData['contact_type'],
                            'contact_value' => $contactData['contact_value'],
                            'is_primary' => false,
                        ]);
                        $contactIdsForSync[] = $newContact->contact_ID;
                    }
                }
            }

            if (!empty($validatedData['new_contacts'])) {
                foreach ($validatedData['new_contacts'] as $newContactData) {
                    if (!empty($newContactData['contact_value'])) {
                        $newContact = ContactNumber::create([
                            'contact_type' => $newContactData['contact_type'],
                            'contact_value' => $newContactData['contact_value'],
                            'is_primary' => false,
                        ]);
                        $contactIdsForSync[] = $newContact->contact_ID;
                    }
                }
            }
            $warehouse->contacts()->sync($contactIdsForSync);


            $sectionIdsForSync = [];
            $currentSectionIds = $warehouse->sections->pluck('section_ID')->toArray();
            $sectionsToKeep = [];

            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                foreach ($validatedData['sections'] as $sectionData) {
                    if (isset($sectionData['section_ID']) && !empty($sectionData['section_ID'])) {
                        $section = WarehouseSection::find($sectionData['section_ID']);
                        if ($section) {
                            $section->update([
                                's_Capacity' => $sectionData['s_Capacity'],
                                'section_name' => $sectionData['section_name'],
                                'temperature_zone' => $sectionData['temperature_zone'],
                            ]);
                            $sectionIdsForSync[] = $section->section_ID;
                            $sectionsToKeep[] = $section->section_ID;
                        }
                    } else {
                        $newSection = WarehouseSection::create([
                            's_Capacity' => $sectionData['s_Capacity'],
                            'section_name' => $sectionData['section_name'],
                            'temperature_zone' => $sectionData['temperature_zone'],
                        ]);
                        $sectionIdsForSync[] = $newSection->section_ID;
                        $sectionsToKeep[] = $newSection->section_ID;
                    }
                }
            }

            $sectionsToDelete = array_diff($currentSectionIds, $sectionsToKeep);

            foreach ($sectionsToDelete as $sectionIdToDelete) {
                $section = WarehouseSection::find($sectionIdToDelete);
                if ($section) {
                    $warehouse->sections()->detach($section->section_ID);
                    $section->subsections()->detach();
                    $section->delete();
                }
            }

            $warehouse->sections()->sync($sectionIdsForSync);


            DB::commit();

            return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update warehouse: ' . $e->getMessage()]);
        }
    }

    // public function show(Warehouse $warehouse)
    // {
    //     $warehouse->load('address', 'contacts', 'sections.subsections');
    //     return response()->json($warehouse);
    // }

    public function destroy(Warehouse $warehouse)
    {
        DB::transaction(function () use ($warehouse) {
            $sectionIDs = DB::table('includes')
                ->where('warehouse_ID', $warehouse->warehouse_ID)
                ->pluck('section_ID');

            if ($sectionIDs->isNotEmpty()) {
                DB::table('divides')->whereIn('section_ID', $sectionIDs)->delete();
                DB::table('warehouse_section')->whereIn('section_ID', $sectionIDs)->delete();
            }

            $warehouse->contacts()->detach();

            $warehouse->delete();
        });

        return redirect()->route('warehouse.index')->with('success', 'Warehouse deleted successfully.');
    }
}
