<?php
/**
 * The template for displaying pages
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <?php
    while ( have_posts() ) : the_post();
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
        <!-- 页面标题 -->
        <h2 class="post-title">
            <?php the_title(); ?>
        </h2>

        <!-- 页面元信息（可选） -->
        <?php if ( get_edit_post_link() ) : ?>
            <ul class="post-meta group">
                <li>
                    <i class="fa fa-clock-o"></i>
                    <?php
                    printf(
                        __( '更新于：%s', 'moe' ),
                        get_the_modified_date()
                    );
                    ?>
                </li>
                <li>
                    <i class="fa fa-user"></i>
                    <?php the_author(); ?>
                </li>
            </ul>
        <?php endif; ?>

        <!-- 页面特色图片 -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail( 'moe-large' ); ?>
            </div>
        <?php endif; ?>

        <!-- 页面内容 -->
        <div class="entry">
            <?php
            the_content();

            wp_link_pages( array(
                'before'      => '<div class="page-links">' . __( '页面：', 'moe' ),
                'after'       => '</div>',
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            ) );
            ?>
        </div>

        <?php
        // 如果页面开启了评论，显示评论区域
        if ( comments_open() || get_comments_number() ) :
        ?>
            <div class="commt_box">
                <a class="loli" href="#comments">
                    <i class="fa fa-comments-o"></i>
                    <?php _e( '评论', 'moe' ); ?>
                    <span><?php echo get_comments_number(); ?><?php _e( '条', 'moe' ); ?></span>
                </a>
                
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    </article>

    <?php
    endwhile; // End of the loop.
    ?>
</div><!-- /#page -->

<?php
// 显示侧边栏
if ( is_active_sidebar( 'sidebar-1' ) ) {
    get_sidebar();
}
?>

<?php get_footer(); ?>

