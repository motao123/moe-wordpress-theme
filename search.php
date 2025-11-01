<?php
/**
 * The template for displaying search results
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <!-- 搜索结果头部 -->
    <header class="search-header">
        <h1 class="search-title">
            <i class="fa fa-search"></i>
            <?php
            printf(
                __( '搜索结果：%s', 'moe' ),
                '<span class="search-query">' . get_search_query() . '</span>'
            );
            ?>
        </h1>
        
        <?php if ( have_posts() ) : ?>
            <p class="search-results-count">
                <?php
                global $wp_query;
                printf(
                    _n( '找到 %s 个结果', '找到 %s 个结果', $wp_query->found_posts, 'moe' ),
                    '<strong>' . number_format_i18n( $wp_query->found_posts ) . '</strong>'
                );
                ?>
            </p>
        <?php endif; ?>
    </header>

    <?php if ( have_posts() ) : ?>

        <?php
        // 开始文章循环
        while ( have_posts() ) : the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post search-result' ); ?>>
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
                    } else {
                        _e( '未分类', 'moe' );
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
            </ul>

            <!-- 文章特色图片 -->
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'moe-medium' ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- 文章摘要 -->
            <div class="entry search-excerpt">
                <?php
                if ( has_excerpt() ) {
                    $excerpt = get_the_excerpt();
                } else {
                    // 显示带高亮的搜索结果摘要
                    $content = get_the_content();
                    $content = wp_strip_all_tags( $content );
                    $excerpt = wp_trim_words( $content, 80, '...' );
                }
                
                // 高亮搜索关键词（支持多关键词）
                $search_query = get_search_query();
                if ( ! empty( $search_query ) ) {
                    // 分词支持
                    $keywords = explode( ' ', $search_query );
                    foreach ( $keywords as $keyword ) {
                        if ( ! empty( $keyword ) ) {
                            $excerpt = preg_replace(
                                '/(' . preg_quote( $keyword, '/' ) . ')/iu',
                                '<mark>$1</mark>',
                                $excerpt
                            );
                        }
                    }
                }
                
                echo $excerpt;
                ?>
                
                <!-- 显示匹配标签 -->
                <?php
                $post_tags = get_the_tags();
                if ( $post_tags && ! empty( $search_query ) ) {
                    $matching_tags = array();
                    foreach ( $post_tags as $tag ) {
                        if ( stripos( $tag->name, $search_query ) !== false ) {
                            $matching_tags[] = $tag;
                        }
                    }
                    if ( ! empty( $matching_tags ) ) {
                        echo '<div class="matching-tags"><i class="fa fa-tags"></i> ';
                        _e( '相关标签：', 'moe' );
                        foreach ( $matching_tags as $tag ) {
                            echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link">' . esc_html( $tag->name ) . '</a> ';
                        }
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- 阅读更多 -->
            <div class="read-more">
                <a href="<?php the_permalink(); ?>" class="more-link">
                    <?php _e( '查看详情', 'moe' ); ?> <i class="fa fa-angle-double-right"></i>
                </a>
            </div>
        </article>

        <?php
        endwhile;
        ?>

        <!-- 分页导航 -->
        <?php moe_pagination(); ?>

    <?php else : ?>

        <!-- 没有找到搜索结果 -->
        <article class="post no-results">
            <h2 class="post-title">
                <i class="fa fa-frown-o"></i>
                <?php _e( '没有找到相关内容', 'moe' ); ?>
            </h2>
            
            <div class="entry">
                <div class="no-results-content">
                    <p><?php _e( '抱歉，没有找到与您的搜索条件匹配的内容。', 'moe' ); ?></p>

                    <h3><?php _e( '搜索建议：', 'moe' ); ?></h3>
                    <ul class="search-tips">
                        <li><?php _e( '检查关键词拼写是否正确', 'moe' ); ?></li>
                        <li><?php _e( '尝试使用更通用的关键词', 'moe' ); ?></li>
                        <li><?php _e( '尝试使用不同的关键词', 'moe' ); ?></li>
                        <li><?php _e( '减少关键词数量', 'moe' ); ?></li>
                    </ul>

                    <!-- 重新搜索 -->
                    <div class="new-search">
                        <h3><?php _e( '重新搜索', 'moe' ); ?></h3>
                        <?php get_search_form(); ?>
                    </div>

                    <!-- 浏览分类 -->
                    <?php
                    $categories = get_categories( array(
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'number'     => 10,
                        'hide_empty' => true,
                    ) );

                    if ( $categories ) :
                    ?>
                        <div class="browse-categories">
                            <h3><?php _e( '或浏览这些分类', 'moe' ); ?></h3>
                            <ul>
                                <?php foreach ( $categories as $category ) : ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                                            <i class="fa fa-folder-o"></i>
                                            <?php echo esc_html( $category->name ); ?>
                                            (<?php echo $category->count; ?>)
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- 最新文章 -->
                    <?php
                    $recent_posts = wp_get_recent_posts( array(
                        'numberposts' => 5,
                        'post_status' => 'publish',
                    ) );

                    if ( $recent_posts ) :
                    ?>
                        <div class="recent-posts">
                            <h3><?php _e( '最新文章', 'moe' ); ?></h3>
                            <ul>
                                <?php foreach ( $recent_posts as $post ) : ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                                            <i class="fa fa-angle-right"></i>
                                            <?php echo esc_html( $post['post_title'] ); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php
                    wp_reset_postdata();
                    endif;
                    ?>
                </div>
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
/* 搜索结果页面样式优化 */
.search-header {
    background: linear-gradient(135deg, var(--moe-header-gradient-start) 0%, var(--moe-header-gradient-end) 100%);
    border: 2px solid var(--moe-header-border-color);
    color: #333;
    padding: 40px 30px;
    border-radius: 12px;
    margin-bottom: 40px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(255, 182, 193, 0.2);
    position: relative;
    overflow: hidden;
}

.search-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: searchPulse 8s ease-in-out infinite;
}

@keyframes searchPulse {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(-20px, -20px); }
}

.search-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
    color: var(--moe-header-title-color);
}

.search-title i {
    margin-right: 10px;
    animation: searchIcon 2s ease-in-out infinite;
}

@keyframes searchIcon {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.search-query {
    color: var(--moe-header-title-color);
    font-weight: bold;
    font-style: italic;
}

.search-results-count {
    font-size: 16px;
    color: #666;
    margin: 0;
    position: relative;
    z-index: 1;
}

.search-results-count strong {
    color: #ffd93d;
    font-size: 20px;
}

/* 搜索结果项样式 */
.search-result {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.search-result:hover {
    border-color: #24a5db;
    box-shadow: 0 4px 16px rgba(36, 165, 219, 0.2);
    transform: translateY(-2px);
}

.search-result .post-title a {
    color: #333;
    transition: color 0.3s ease;
}

.search-result .post-title a:hover {
    color: #24a5db;
}

.search-result mark {
    background: linear-gradient(135deg, #ffd93d 0%, #ffb93d 100%);
    color: #333;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(255, 217, 61, 0.3);
}

.search-excerpt {
    line-height: 1.8;
    color: #666;
}

.matching-tags {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed #e0e0e0;
    font-size: 14px;
    color: #999;
}

.matching-tags i {
    color: #24a5db;
    margin-right: 5px;
}

.matching-tags .tag-link {
    display: inline-block;
    padding: 3px 10px;
    margin: 3px;
    background: #f0f0f0;
    color: #666;
    border-radius: 15px;
    font-size: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.matching-tags .tag-link:hover {
    background: #24a5db;
    color: #fff;
    transform: translateY(-1px);
}

.search-result .read-more {
    margin-top: 15px;
}

.search-result .more-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 20px;
    background: linear-gradient(135deg, #24a5db 0%, #1a8fbd 100%);
    color: #fff;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-result .more-link:hover {
    transform: translateX(3px);
    box-shadow: 0 4px 12px rgba(36, 165, 219, 0.3);
}

.no-results-content {
    text-align: center;
}

.no-results-content h3 {
    font-size: 20px;
    font-weight: bold;
    margin: 30px 0 15px;
    color: #333;
}

.search-tips {
    list-style: none;
    padding: 0;
    text-align: left;
    max-width: 500px;
    margin: 0 auto 30px;
}

.search-tips li {
    padding: 8px 0;
    border-bottom: 1px dashed #eee;
}

.search-tips li:before {
    content: "• ";
    color: #24a5db;
    font-weight: bold;
    margin-right: 8px;
}

.new-search,
.browse-categories,
.recent-posts {
    margin: 30px 0;
    text-align: left;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.new-search form {
    max-width: 100%;
}

.browse-categories ul,
.recent-posts ul {
    list-style: none;
    padding: 0;
}

.browse-categories ul li,
.recent-posts ul li {
    padding: 8px 0;
    border-bottom: 1px dashed #eee;
}

.browse-categories ul li:last-child,
.recent-posts ul li:last-child {
    border-bottom: none;
}

.browse-categories ul li a,
.recent-posts ul li a {
    color: #24a5db;
    transition: all 0.3s ease;
}

.browse-categories ul li a:hover,
.recent-posts ul li a:hover {
    color: #1a8fbd;
    padding-left: 5px;
}

@media screen and (max-width: 768px) {
    .search-header {
        padding: 30px 20px;
    }
    
    .search-title {
        font-size: 22px;
    }
    
    .no-results-content h3 {
        font-size: 18px;
    }
}
</style>

