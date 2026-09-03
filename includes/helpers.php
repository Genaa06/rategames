<?php
// ==========================================
// دوال مشتركة: نمط الغلاف حسب التصنيف + النجوم
// ==========================================

// نولّد "غلاف" جمالي لكل لعبة بتدرج لوني مرتبط بتصنيفها

function categoryStyle(string $category): array {
    $map = [
        'أكشن'        => ['from' => '#ff6b6b', 'to' => '#c92a2a', 'glow' => '#ff6b6b'],
        'رياضة'       => ['from' => '#51cf66', 'to' => '#2f9e44', 'glow' => '#51cf66'],
        'استراتيجية'  => ['from' => '#9775fa', 'to' => '#5f3dc4', 'glow' => '#9775fa'],
        'رعب'         => ['from' => '#495057', 'to' => '#1a1a2e', 'glow' => '#f03e3e'],
        'سباق'        => ['from' => '#22d3ee', 'to' => '#0e7490', 'glow' => '#22d3ee'],
        'ألغاز'       => ['from' => '#f783ac', 'to' => '#c2255c', 'glow' => '#f783ac'],
        'مغامرات'     => ['from' => '#ffa94d', 'to' => '#e8590c', 'glow' => '#ffa94d'],
        'محاكاة'      => ['from' => '#4dabf7', 'to' => '#1864ab', 'glow' => '#4dabf7'],
        'قتال'        => ['from' => '#fa5252', 'to' => '#862e2e', 'glow' => '#fa5252'],
    ];
    return $map[$category] ?? ['from' => '#fbbf24', 'to' => '#b45309', 'glow' => '#fbbf24'];
}

function coverGradient(string $category): string {
    $c = categoryStyle($category);
    return "linear-gradient(145deg, {$c['from']}, {$c['to']})";
}

function starsHtml(float $avg, string $size = ''): string {
    $full = (int) round($avg);
    $full = max(0, min(5, $full));
    $cls = $size ? " {$size}" : '';
    return "<div class='stars{$cls}'>" . str_repeat('⭐', $full) . str_repeat('☆', 5 - $full) . "</div>";
}
