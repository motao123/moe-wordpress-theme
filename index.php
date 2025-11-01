<?php
/**
 * The main template file
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <?php if ( have_posts() ) : ?>
        
        <?php
        // 显示面包屑导航
        if ( ! is_home() && ! is_front_page() ) {
            moe_breadcrumbs();
        }
        ?>

        <?php
        // 开始文章循环
        while ( have_posts() ) : the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post group' ); ?>>
            <!-- 文章标题 -->
            <h2 class="post-title">
                <?php
                // 显示置顶标识
                if ( is_sticky() && is_home() ) {
                    echo '<i class="fa fa-thumb-tack" title="' . esc_attr__( '置顶', 'moe' ) . '"></i> ';
                }
                ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <!-- 文章摘要 -->
            <div class="theme-excerpt">
                <?php
                if ( has_excerpt() ) {
                    echo wp_trim_words( get_the_excerpt(), 100, '...' );
                } else {
                    echo wp_trim_words( get_the_content(), 100, '...' );
                }
                ?>
            </div>

            <!-- 文章缩略图 -->
            <div class="browser group">
                <div class="browser-view">
                    <a href="<?php the_permalink(); ?>">
                        <span class="b-overlay">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/prebg.png" alt="overlay">
                        </span>
                        <?php 
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail( 'moe-large', array( 'alt' => get_the_title() ) );
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/images/default.jpg" alt="' . get_the_title() . '">';
                        }
                        ?>
                    </a>
                </div>
            </div>

            <!-- 文章元信息 -->
            <ul class="post-meta bottom group">
                <li>
                    <i class="fa fa-folder-o"></i>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                    } else {
                        _e( '未分类', 'moe' );
                    }
                    ?>
                </li>

                <li>
                    <i class="fa fa-clock-o"></i>
                    <?php echo get_the_date( 'Y-n-j' ); ?>
                </li>

                <li>
                    <a href="<?php comments_link(); ?>">
                        <i class="fa fa-comment"></i>
                        <?php
                        $comments_count = get_comments_number();
                        if ( $comments_count == 0 ) {
                            echo 'NO COMMENTS';
                        } else {
                            echo $comments_count . ' COMMENTS';
                        }
                        ?>
                    </a>
                </li>
            </ul>
        </article>

        <?php
        endwhile;
        ?>

        <!-- Ajax 加载更多按钮 -->
        <?php if ( function_exists( 'moe_add_load_more_button' ) ) {
            moe_add_load_more_button();
        } ?>

        <!-- 分页导航（备用） -->
        <nav id="loli" class="pagination group" style="display:none;">
            <?php moe_pagination(); ?>
        </nav>

    <?php else : ?>

        <!-- 没有找到文章 -->
        <article class="post">
            <h2 class="post-title"><?php _e( '未找到内容', 'moe' ); ?></h2>
            <div class="entry">
                <p><?php _e( '抱歉，没有找到相关内容。请尝试使用搜索功能。', 'moe' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </article>

    <?php endif; ?>
</div><!-- /#page -->

<?php
// 显示侧边栏
if ( is_active_sidebar( 'sidebar-1' ) ) {
    get_sidebar();
}
?>

<?php get_footer(); ?>

