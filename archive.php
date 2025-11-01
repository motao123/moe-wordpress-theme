<?php
/**
 * The template for displaying archive pages
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <?php if ( have_posts() ) : ?>

        <?php if ( is_date() && function_exists( 'moe_get_posts_by_year_month' ) ) : ?>
            <!-- 日期归档统计信息 -->
            <header class="archive-header">
                <div class="archive-stats">
                    <h2 class="archive-stats-title">
                        <?php _e( '归档统计', 'moe' ); ?>
                    </h2>
                    <div class="archive-stats-grid">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $wp_query->found_posts; ?></span>
                            <span class="stat-label"><?php _e( '篇文章', 'moe' ); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count( get_categories( array( 'hide_empty' => false ) ) ); ?></span>
                            <span class="stat-label"><?php _e( '个分类', 'moe' ); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count( get_tags( array( 'hide_empty' => false ) ) ); ?></span>
                            <span class="stat-label"><?php _e( '个标签', 'moe' ); ?></span>
                        </div>
                    </div>
                </div>
            </header>
        
        <?php endif; ?>
        
        <?php if ( is_date() && function_exists( 'moe_get_posts_by_year_month' ) ) : ?>
            <!-- 时间轴视图 -->
            <div class="archive-timeline">
                <?php
                $posts_by_date = moe_get_posts_by_year_month( $wp_query );
                
                foreach ( $posts_by_date as $year => $months ) :
                ?>
                    <div class="timeline-year">
                        <h2 class="year-label"><?php echo $year; ?> <?php _e( '年', 'moe' ); ?></h2>
                        
                        <?php foreach ( $months as $month => $data ) : ?>
                            <div class="timeline-month">
                                <h3 class="month-label"><?php echo $data['name']; ?></h3>
                                
                                <ul class="timeline-posts">
                                    <?php foreach ( $data['posts'] as $post ) : ?>
                                        <li class="timeline-post">
                                            <h4 class="timeline-post-title">
                                                <a href="<?php echo esc_url( $post['link'] ); ?>">
                                                    <?php echo esc_html( $post['title'] ); ?>
                                                </a>
                                            </h4>
                                            <div class="timeline-post-meta">
                                                <span>
                                                    <i class="fa fa-calendar-o"></i>
                                                    <?php echo $post['date']; ?>
                                                </span>
                                                <?php if ( ! empty( $post['category'] ) ) : ?>
                                                    <span>
                                                        <i class="fa fa-folder-o"></i>
                                                        <a href="<?php echo esc_url( get_category_link( $post['category'][0]->term_id ) ); ?>">
                                                            <?php echo esc_html( $post['category'][0]->name ); ?>
                                                        </a>
                                                    </span>
                                                <?php endif; ?>
                                                <span>
                                                    <i class="fa fa-comments-o"></i>
                                                    <?php echo $post['comments']; ?> <?php _e( '条评论', 'moe' ); ?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
        
        <!-- 标准列表视图 -->
        <header class="archive-header">
            <h1 class="archive-title">
                <?php
                if ( is_category() ) :
                    single_cat_title( '<i class="fa fa-folder-open"></i> ' );
                elseif ( is_tag() ) :
                    single_tag_title( '<i class="fa fa-tag"></i> ' );
                elseif ( is_author() ) :
                    the_author_meta( 'display_name' );
                    echo ' <i class="fa fa-user"></i>';
                elseif ( is_day() ) :
                    echo '<i class="fa fa-calendar"></i> ';
                    echo get_the_date();
                elseif ( is_month() ) :
                    echo '<i class="fa fa-calendar"></i> ';
                    echo get_the_date( 'Y年n月' );
                elseif ( is_year() ) :
                    echo '<i class="fa fa-calendar"></i> ';
                    echo get_the_date( 'Y年' );
                else :
                    _e( '归档', 'moe' );
                endif;
                ?>
            </h1>

            <?php
            // 显示分类/标签描述
            if ( is_category() || is_tag() ) :
                $description = term_description();
                if ( $description ) :
            ?>
                <div class="archive-description">
                    <?php echo $description; ?>
                </div>
            <?php
                endif;
            endif;
            ?>

            <?php
            // 显示作者信息
            if ( is_author() ) :
                $author_id = get_query_var( 'author' );
            ?>
                <div class="author-info">
                    <div class="author-avatar">
                        <?php echo get_avatar( $author_id, 80 ); ?>
                    </div>
                    <div class="author-bio">
                        <?php
                        $author_description = get_the_author_meta( 'description', $author_id );
                        if ( $author_description ) {
                            echo '<p>' . esc_html( $author_description ) . '</p>';
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </header>

        <?php
        // 面包屑导航
        moe_breadcrumbs();
        ?>

        <?php
        // 开始文章循环
        while ( have_posts() ) : the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
            <!-- 文章标题 -->
            <h2 class="post-title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <!-- 文章元信息 -->
            <ul class="post-meta group">
                <li>
                    <i class="fa fa-user"></i>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                        <?php the_author(); ?>
                    </a>
                </li>
                
                <li>
                    <i class="fa fa-folder-o"></i>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                    }
                    ?>
                </li>

                <li>
                    <i class="fa fa-clock-o"></i>
                    <?php echo get_the_date(); ?>
                </li>

                <li>
                    <a href="<?php comments_link(); ?>">
                        <i class="fa fa-comment"></i>
                        <?php
                        $comments_count = get_comments_number();
                        if ( $comments_count == 0 ) {
                            _e( '抢沙发', 'moe' );
                        } else {
                            printf( _n( '%s 条评论', '%s 条评论', $comments_count, 'moe' ), number_format_i18n( $comments_count ) );
                        }
                        ?>
                    </a>
                </li>

                <li>
                    <i class="fa fa-eye"></i>
                    <?php echo moe_get_post_views( get_the_ID() ); ?> <?php _e( '次浏览', 'moe' ); ?>
                </li>
            </ul>

            <!-- 文章特色图片 -->
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'moe-large' ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- 文章摘要 -->
            <div class="entry">
                <?php
                if ( has_excerpt() ) {
                    the_excerpt();
                } else {
                    echo wp_trim_words( get_the_content(), 120, '...' );
                }
                ?>
            </div>

            <!-- 阅读更多 -->
            <div class="read-more">
                <a href="<?php the_permalink(); ?>" class="more-link">
                    <?php _e( '阅读全文', 'moe' ); ?> <i class="fa fa-angle-double-right"></i>
                </a>
            </div>
        </article>

        <?php
        endwhile;
        ?>

        <!-- Ajax 加载更多按钮 -->
        <?php if ( function_exists( 'moe_add_load_more_button' ) ) {
            moe_add_load_more_button();
        } ?>

        <!-- 分页导航（备用） -->
        <div style="display:none;">
            <?php moe_pagination(); ?>
        </div>
        
        <?php endif; // End is_date() check ?>

    <?php else : ?>

        <!-- 没有找到文章 -->
        <article class="post">
            <h2 class="post-title"><?php _e( '未找到内容', 'moe' ); ?></h2>
            <div class="entry">
                <p><?php _e( '抱歉，该归档中没有内容。', 'moe' ); ?></p>
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

<style>
.archive-header {
    background: linear-gradient(135deg, var(--moe-header-gradient-start) 0%, var(--moe-header-gradient-end) 100%);
    border: 2px solid var(--moe-header-border-color);
    color: #333;
    padding: 40px 30px;
    border-radius: 8px;
    margin-bottom: 40px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(255, 182, 193, 0.2);
}

.archive-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 15px;
    color: var(--moe-header-title-color);
}

.archive-title i {
    color: var(--moe-header-title-color);
    opacity: 0.8;
    margin-right: 8px;
}

.archive-description {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
}

.author-info {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    gap: 20px;
}

.author-avatar img {
    border-radius: 50%;
    border: 4px solid #FFB6C1;
}

.author-bio {
    text-align: left;
    max-width: 500px;
}

.author-bio p {
    margin: 0;
    opacity: 0.9;
}

@media screen and (max-width: 768px) {
    .archive-header {
        padding: 30px 20px;
    }
    
    .archive-title {
        font-size: 24px;
    }
    
    .author-info {
        flex-direction: column;
        text-align: center;
    }
    
    .author-bio {
        text-align: center;
    }
}
</style>

