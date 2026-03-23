<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * DB-agnostic date parts for raw SQL (PostgreSQL + MySQL).
 * Use for MONTH(), YEAR(), DATE_FORMAT('%b') so the app works on Supabase (PostgreSQL).
 */
class DateRangeQueries
{
    /** Column expression for month number (1-12). Use in selectRaw/groupByRaw. */
    public static function monthColumn(string $column): string
    {
        $col = self::quoteColumn($column);
        return DB::connection()->getDriverName() === 'pgsql'
            ? "EXTRACT(MONTH FROM {$col})::integer"
            : "MONTH({$col})";
    }

    /** Column expression for short month name (Jan, Feb, ...). */
    public static function monthNameColumn(string $column): string
    {
        $col = self::quoteColumn($column);
        return DB::connection()->getDriverName() === 'pgsql'
            ? "TO_CHAR({$col}, 'Mon')"
            : "DATE_FORMAT({$col}, '%b')";
    }

    /** Column expression for year. */
    public static function yearColumn(string $column): string
    {
        $col = self::quoteColumn($column);
        return DB::connection()->getDriverName() === 'pgsql'
            ? "EXTRACT(YEAR FROM {$col})::integer"
            : "YEAR({$col})";
    }

    /** For groupByRaw: same expressions as select, e.g. "month_num, month". */
    public static function groupByMonthAndName(string $column): string
    {
        $m = self::monthColumn($column);
        $n = self::monthNameColumn($column);
        return "{$m}, {$n}";
    }

    private static function quoteColumn(string $column): string
    {
        // If it's a function call like COALESCE(...), use as-is; otherwise quote identifier for safety
        if (stripos($column, '(') !== false) {
            return $column;
        }
        return $column;
    }
}
