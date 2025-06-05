<?php

namespace App\Traits;

trait HasYearlyWallets
{
    /**
     * Dapatkan wallet berdasarkan tahun tertentu (otomatis dibuat jika belum ada).
     */
    public function getYearlyWallet(int $year)
    {
        return $this->getWallet((string) $year);
    }

    /**
     * Deposit ke wallet tahun tertentu.
     */
    public function depositToYear(int $year, int|float $amount, array $meta = []): void
    {
        $this->getYearlyWallet($year)->deposit($amount, $meta);
    }

    /**
     * Withdraw dari wallet tahun tertentu (akan throw exception jika tidak cukup saldo).
     */
    public function withdrawFromYear(int $year, int|float $amount, array $meta = []): void
    {
        $this->getYearlyWallet($year)->withdraw($amount, $meta);
    }

    /**
     * Transfer dari tahun ini ke tahun lain.
     */
    public function transferBetweenYears(int $fromYear, int $toYear, int|float $amount, array $meta = []): void
    {
        $this->getYearlyWallet($fromYear)->transfer($this->getYearlyWallet($toYear), $amount, $meta);
    }

    /**
     * Ambil saldo dari tahun tertentu.
     */
    public function balanceForYear(int $year): int|float
    {
        return $this->getYearlyWallet($year)->balance ?? 0;
    }

    public function transferForYear($receiver, int $year, int|float $amount, array $meta = [])
    {
        $senderWallet = $this->getYearlyWallet($year);
        $receiverWallet = $receiver->getYearlyWallet($year);

        return $senderWallet->transfer($receiverWallet, $amount, $meta);
    }
}

