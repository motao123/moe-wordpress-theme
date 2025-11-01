<?php
/**
 * The sidebar containing the main widget area
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="sidebar" class="widget-area" role="complementary">
    <ul>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </ul>
</aside><!-- #sidebar -->

