<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Sra;
use App\Models\SraItem;
use App\Models\Issue;
use App\Models\IssueItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Users
        $this->createUsers();

        // Create Items
        $this->createItems();

        // Create Requisitions
        $this->createRequisitions();

        // Create SRAs
        $this->createSras();

        // Create Issues
        $this->createIssues();

        $this->command->info('Sample data seeded successfully!');
    }

    private function createUsers()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@store.local',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Jane Storekeeper',
                'email' => 'storekeeper@store.local',
                'password' => 'password',
                'role' => 'storekeeper',
                'is_active' => true,
            ],
            [
                'name' => 'David Auditor',
                'email' => 'auditor@store.local',
                'password' => 'password',
                'role' => 'auditor',
                'is_active' => true,
            ],
            [
                'name' => 'Principal Officer',
                'email' => 'principal@store.local',
                'password' => 'password',
                'role' => 'principal',
                'is_active' => true,
            ],
            [
                'name' => 'John Requester',
                'email' => 'requester@store.local',
                'password' => 'password',
                'role' => 'requester',
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Requester',
                'email' => 'sarah.req@store.local',
                'password' => 'password',
                'role' => 'requester',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }

    private function createItems()
    {
        $items = [
            [
                'name' => 'Office Chairs',
                'category' => 'Furniture',
                'unit' => 'Piece',
                'min_stock' => 5,
                'max_stock' => 20,
            ],
            [
                'name' => 'A4 Paper Bundles',
                'category' => 'Stationery',
                'unit' => 'Bundle',
                'min_stock' => 10,
                'max_stock' => 50,
            ],
            [
                'name' => 'Ink Cartridges',
                'category' => 'Consumables',
                'unit' => 'Piece',
                'min_stock' => 3,
                'max_stock' => 15,
            ],
            [
                'name' => 'Desk Lamps',
                'category' => 'Furniture',
                'unit' => 'Piece',
                'min_stock' => 2,
                'max_stock' => 10,
            ],
            [
                'name' => 'USB Cables',
                'category' => 'IT Equipment',
                'unit' => 'Piece',
                'min_stock' => 5,
                'max_stock' => 30,
            ],
            [
                'name' => 'Notebooks',
                'category' => 'Stationery',
                'unit' => 'Piece',
                'min_stock' => 20,
                'max_stock' => 100,
            ],
            [
                'name' => 'Printer Toner',
                'category' => 'Consumables',
                'unit' => 'Piece',
                'min_stock' => 2,
                'max_stock' => 8,
            ],
            [
                'name' => 'Mouse Pads',
                'category' => 'IT Equipment',
                'unit' => 'Piece',
                'min_stock' => 5,
                'max_stock' => 20,
            ],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(['name' => $item['name']], $item);
        }
    }

    private function createRequisitions()
    {
        $requester = User::where('role', 'requester')->first();
        $principal = User::where('role', 'principal')->first();

        if (!$requester) return;

        $requisitions = [
            [
                'requested_by' => $requester->id,
                'status' => 'approved',
                'approved_by' => $principal ? $principal->id : null,
            ],
            [
                'requested_by' => $requester->id,
                'status' => 'pending',
            ],
            [
                'requested_by' => $requester->id,
                'status' => 'rejected',
                'approved_by' => $principal ? $principal->id : null,
            ],
        ];

        foreach ($requisitions as $req) {
            Requisition::create($req);
        }

        // Add items to requisitions
        $this->attachRequisitionItems();
    }

    private function attachRequisitionItems()
    {
        $requisitions = Requisition::all();
        $items = Item::all();

        if ($requisitions->isEmpty() || $items->isEmpty()) return;

        // First approved requisition
        if ($req1 = $requisitions->where('status', 'approved')->first()) {
            RequisitionItem::create([
                'requisition_id' => $req1->id,
                'item_id' => $items[0]->id,
                'quantity_requested' => 3,
            ]);
            RequisitionItem::create([
                'requisition_id' => $req1->id,
                'item_id' => $items[1]->id,
                'quantity_requested' => 2,
            ]);
        }

        // First pending requisition
        if ($req2 = $requisitions->where('status', 'pending')->first()) {
            RequisitionItem::create([
                'requisition_id' => $req2->id,
                'item_id' => $items[2]->id,
                'quantity_requested' => 5,
            ]);
            RequisitionItem::create([
                'requisition_id' => $req2->id,
                'item_id' => $items[4]->id,
                'quantity_requested' => 10,
            ]);
        }
    }

    private function createSras()
    {
        $storekeeper = User::where('role', 'storekeeper')->first();

        if (!$storekeeper) return;

        $sras = [
            [
                'sra_number' => 'SRA-2026-001',
                'supplier_details' => 'Global Supplies Ltd | INV-00543 | WB-9543',
                'created_by' => $storekeeper->id,
                'status' => 'approved',
                'signed_storekeeper' => true,
                'signed_auditor' => true,
                'signed_principal' => true,
            ],
            [
                'sra_number' => 'SRA-2026-002',
                'supplier_details' => 'Tech Imports Inc | INV-00562 | WB-9652',
                'created_by' => $storekeeper->id,
                'status' => 'pending',
                'signed_storekeeper' => true,
                'signed_auditor' => false,
                'signed_principal' => false,
            ],
        ];

        foreach ($sras as $sra) {
            Sra::create($sra);
        }

        $this->attachSraItems();
    }

    private function attachSraItems()
    {
        $sras = Sra::all();
        $items = Item::all();

        if ($sras->isEmpty() || $items->isEmpty()) return;

        if ($sra1 = $sras->where('sra_number', 'SRA-2026-001')->first()) {
            SraItem::create([
                'sra_id' => $sra1->id,
                'item_id' => $items[0]->id,
                'quantity' => 5,
            ]);
            SraItem::create([
                'sra_id' => $sra1->id,
                'item_id' => $items[1]->id,
                'quantity' => 10,
            ]);
        }

        if ($sra2 = $sras->where('sra_number', 'SRA-2026-002')->first()) {
            SraItem::create([
                'sra_id' => $sra2->id,
                'item_id' => $items[4]->id,
                'quantity' => 15,
            ]);
        }
    }

    private function createIssues()
    {
        $storekeeper = User::where('role', 'storekeeper')->first();
        $requisition = Requisition::where('status', 'approved')->first();

        if (!$storekeeper || !$requisition) return;

        Issue::create([
            'requisition_id' => $requisition->id,
            'issued_by' => $storekeeper->id,
            'received_by' => null,
        ]);
    }
}