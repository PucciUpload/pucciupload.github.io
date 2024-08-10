<?php
declare(strict_types = 1);

const WII_PLAZA = new MiiType("WII_PL", 779_968, 74, 0x4, 100);
const WII_PARADE = new MiiType("WII_PA", 779_968, 64, 0x1F1E0, 10_000, 10);
const WIIU_MAKER = new MiiType("WIIU_MA", 276_544, 92, 0x8, 3_000);
const _3DS_MAKER = new MiiType("3DS_MA", 310_560, 92, 0x8, 100);

final class MiiType
{
    private string $prefix;
    private int $dbSize;
    private int $size;
    private int $offset;
    private int $limit;
    private int $padding;

    public function __construct(string $prefix, int $dbSize, int $size, int $offset, int $limit, int $padding = 0)
    {
        $this -> prefix = $prefix;
        $this -> dbSize = $dbSize;
        $this -> size = $size;
        $this -> offset = $offset;
        $this -> limit = $limit;
        $this -> padding = $padding;
    }

    public static function values(): array
    {
        return array(WII_PLAZA, WII_PARADE, WIIU_MAKER, _3DS_MAKER);
    }

    public function getPrefix(): string
    {
        return $this -> prefix;
    }

    public function getDbSize(): int
    {
        return $this -> dbSize;
    }

    public function getSize(): int
    {
        return $this -> size;
    }

    public function getOffset(): int
    {
        return $this -> offset;
    }

    public function getLimit(): int
    {
        return $this -> limit;
    }

    public function getPadding(): int
    {
        return $this -> padding;
    }
}