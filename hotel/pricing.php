<?php
/**
 * pricing.php — Reusable pricing helper for bookHotel
 * Include this file wherever pricing is needed.
 *
 * Tax rates:
 *   Room price ≤ ₹2,500   → 0% GST (budget)
 *   Room price ≤ ₹7,500   → 12% GST
 *   Room price > ₹7,500   → 18% GST
 * Service charge: ₹200 flat (applied once per booking, not per night on cards)
 */

define('SERVICE_CHARGE', 200);

function bhTaxRate(float $price): float {
    if ($price <= 2500) return 0.00;
    if ($price <= 7500) return 0.12;
    return 0.18;
}

function bhCalcPricing(float $price): array {
    $rate     = bhTaxRate($price);
    $tax      = round($price * $rate);
    $total    = $price + $tax;
    $taxPct   = (int)($rate * 100);
    return [
        'price'       => $price,
        'tax'         => $tax,
        'tax_pct'     => $taxPct,
        'total'       => $total,
        'service'     => SERVICE_CHARGE,
        'grand_total' => $total + SERVICE_CHARGE,
    ];
}

/**
 * Renders a compact price block for hotel listing cards.
 * Shows: original (struck), discounted, tax line, total.
 *
 * @param float $price       Discounted price per night
 * @param float $original    Original MRP (0 to hide)
 * @param bool  $showTotal   Whether to show total per night
 */
function bhPriceBlock(float $price, float $original = 0, bool $showTotal = true): void {
    $p = bhCalcPricing($price);
    echo '<div class="price-block">';
    if ($original > 0 && $original > $price) {
        echo '<span class="price-block__original">₹' . number_format($original) . '</span>';
    }
    echo '<div class="price-block__main">₹' . number_format($price) . '<span>/night</span></div>';
    if ($p['tax'] > 0) {
        echo '<div class="price-block__tax"><i class="bi bi-info-circle"></i>+ ₹' . number_format($p['tax']) . ' taxes (' . $p['tax_pct'] . '% GST)</div>';
    } else {
        echo '<div class="price-block__tax"><i class="bi bi-check-circle text-success"></i>No GST on this price</div>';
    }
    if ($showTotal) {
        echo '<div class="price-block__total"><i class="bi bi-receipt"></i>Total: ₹' . number_format($p['total']) . '/night</div>';
    }
    echo '</div>';
}

/**
 * Renders a detailed price breakdown box.
 * Used on hotel-details, guest-details, review-booking, payment pages.
 *
 * @param float  $price    Room price per night
 * @param int    $nights   Number of nights
 * @param float  $discount Discount fraction (e.g. 0.35 for 35% off)
 * @param float  $original Original price per night
 */
function bhPriceBreakdown(float $price, int $nights = 1, float $discount = 0, float $original = 0): void {
    $roomCost  = $price * $nights;
    $discAmt   = round($roomCost * $discount);
    $afterDisc = $roomCost - $discAmt;
    $p         = bhCalcPricing($afterDisc / max(1, $nights)); // tax on discounted per-night
    $tax       = round($afterDisc * bhTaxRate($price));
    $svc       = SERVICE_CHARGE;
    $grand     = $afterDisc + $tax + $svc;
    $savings   = $discAmt;
    if ($original > 0) $savings = ($original - $price) * $nights;

    echo '<div class="price-breakdown-box">';
    // Room cost
    echo '<div class="row-item"><span class="label">Room Cost (' . $nights . ' night' . ($nights > 1 ? 's' : '') . ')</span><span class="value">₹' . number_format($roomCost) . '</span></div>';
    // Discount
    if ($discAmt > 0 || ($original > 0 && $original > $price)) {
        $showDisc = $original > 0 ? ($original - $price) * $nights : $discAmt;
        echo '<div class="row-item"><span class="label" style="color:#059669">Discount</span><span class="value" style="color:#059669">− ₹' . number_format($showDisc) . '</span></div>';
    }
    // Taxes
    if ($tax > 0) {
        echo '<div class="row-item"><span class="label">GST (' . (int)(bhTaxRate($price) * 100) . '%)</span><span class="value">₹' . number_format($tax) . '</span></div>';
    } else {
        echo '<div class="row-item"><span class="label">GST</span><span class="value" style="color:#059669">₹0 (Exempt)</span></div>';
    }
    // Service charge
    echo '<div class="row-item"><span class="label">Service Charge</span><span class="value">₹' . number_format($svc) . '</span></div>';
    // Total
    echo '<div class="row-item total"><span>Total Payable</span><span class="value">₹' . number_format($grand) . '</span></div>';
    // Savings badge
    if ($savings > 0) {
        echo '<div class="savings-row"><i class="bi bi-piggy-bank-fill"></i>You save ₹' . number_format($savings) . ' on this booking!</div>';
    }
    echo '</div>';
}
?>

