<?php

namespace App\Exports;

use App\Models\LostItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LostItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private string $startDate,
        private string $endDate
    ) {}

    public function collection()
    {
        return LostItem::with(['category', 'location', 'user'])
            ->whereBetween('found_date', [$this->startDate, $this->endDate])
            ->orderBy('found_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ลำดับ', 'ชื่อทรัพย์สิน', 'ประเภท', 'สถานที่พบ',
            'รายละเอียด', 'วันที่พบ', 'วันที่บันทึก', 'สถานะ', 'ผู้รับผิดชอบ',
            'รหัสนิสิต', 'ชื่อผู้รับคืน', 'นามสกุลผู้รับคืน',
            'เบอร์โทร', 'อีเมล', 'วันที่คืน', 'เวลาคืน',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->item_name,
            $item->category?->category_name,
            $item->location?->location_name,
            $item->description,
            $item->found_date?->format('d/m/Y'),
            $item->created_at?->format('d/m/Y'),
            $item->status === 'returned' ? 'คืนแล้ว' : 'รอรับคืน',
            $item->user?->name ?? '',
            $item->student_id,
            $item->owner_first_name,
            $item->owner_last_name,
            $item->tel,
            $item->email,
            $item->returned_date?->format('d/m/Y'),
            $item->returned_timestamp?->format('H:i:s'),
        ];
    }
}
