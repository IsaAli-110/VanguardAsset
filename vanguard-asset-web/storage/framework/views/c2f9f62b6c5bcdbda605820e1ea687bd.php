

<?php
    $max = max($data) ?: 1;
    $min = min($data);
    $range = $max - $min ?: 1;
    $count = count($data);
    $width = 200;
    $height = 40;
    $points = [];
    foreach ($data as $i => $val) {
        $x = $count > 1 ? ($i / ($count - 1)) * $width : $width / 2;
        $y = $height - (($val - $min) / $range) * ($height - 4) - 2;
        $points[] = round($x, 1) . ',' . round($y, 1);
    }
    $polyline = implode(' ', $points);
    // Area fill path
    $areaPath = 'M' . $points[0];
    for ($i = 1; $i < count($points); $i++) {
        $areaPath .= ' L' . $points[$i];
    }
    $areaPath .= ' L' . $width . ',' . $height . ' L0,' . $height . ' Z';
?>
<div class="sparkline-container">
    <svg viewBox="0 0 <?php echo e($width); ?> <?php echo e($height); ?>" preserveAspectRatio="none">
        <defs>
            <linearGradient id="sparkGrad<?php echo e(md5(implode(',', $data))); ?>" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="<?php echo e($color); ?>" stop-opacity="0.4"/>
                <stop offset="100%" stop-color="<?php echo e($color); ?>" stop-opacity="0"/>
            </linearGradient>
        </defs>
        <path d="<?php echo e($areaPath); ?>" fill="url(#sparkGrad<?php echo e(md5(implode(',', $data))); ?>)"/>
        <polyline points="<?php echo e($polyline); ?>" fill="none" stroke="<?php echo e($color); ?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</div>
<?php /**PATH D:\KULIAH\ISA ALI A\OOP\vanguardasset\vanguard-asset-web\resources\views/partials/sparkline.blade.php ENDPATH**/ ?>