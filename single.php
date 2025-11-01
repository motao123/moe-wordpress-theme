<?php
/**
 * The template for displaying single posts
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <?php
    while ( have_posts() ) : the_post();
        
        // 文章浏览量统计
        moe_set_post_views( get_the_ID() );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
        <!-- 面包屑导航 -->
        <?php if ( function_exists( 'moe_breadcrumbs' ) ) {
            moe_breadcrumbs();
        } ?>

        <!-- 文章标题 -->
        <h2 class="post-title">
            <?php
            if ( is_sticky() ) {
                echo '<i class="fa fa-thumb-tack" title="' . esc_attr__( '置顶', 'moe' ) . '"></i> ';
            }
            the_title();
            ?>
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
            
            <?php if ( function_exists( 'moe_display_reading_info' ) ) : ?>
            <li class="reading-stats">
                <?php moe_display_reading_info(); ?>
            </li>
            <?php endif; ?>

            <li>
                <a href="#comments">
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
                <?php the_post_thumbnail( 'moe-large' ); ?>
            </div>
        <?php endif; ?>

        <!-- 文章内容 -->
        <div class="entry">
            <?php
            the_content();

            // 分页链接（用于分页文章）
            wp_link_pages( array(
                'before'      => '<div class="page-links">' . __( '页面：', 'moe' ),
                'after'       => '</div>',
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            ) );
            ?>
        </div>

        <!-- 文章标签 -->
        <?php
        $tags = get_the_tags();
        if ( $tags ) :
        ?>
            <p class="post-tags">
                <i class="fa fa-tags"></i> <?php _e( '标签：', 'moe' ); ?>
                <?php
                foreach ( $tags as $tag ) {
                    echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a> ';
                }
                ?>
            </p>
        <?php endif; ?>

        <!-- 版权声明 -->
        <div class="copyright-notice">
            <div class="copyright-header">
                <i class="fa fa-copyright"></i>
                <strong><?php _e( '版权声明', 'moe' ); ?></strong>
            </div>
            <div class="copyright-body">
                <p class="copyright-author">
                    <i class="fa fa-user"></i>
                    <strong><?php _e( '作者：', 'moe' ); ?></strong>
                    <?php the_author(); ?>
                </p>
                <p class="copyright-link">
                    <i class="fa fa-link"></i>
                    <strong><?php _e( '链接：', 'moe' ); ?></strong>
                    <a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a>
                </p>
                <p class="copyright-license">
                    <i class="fa fa-creative-commons"></i>
                    <strong><?php _e( '许可：', 'moe' ); ?></strong>
                    <?php _e( '本文采用', 'moe' ); ?> 
                    <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank" rel="noopener">CC BY-NC-SA 4.0</a> 
                    <?php _e( '许可协议，转载请注明出处！', 'moe' ); ?>
                </p>
                <p class="copyright-notice-text">
                    <i class="fa fa-info-circle"></i>
                    <?php _e( '商业转载请联系作者获得授权，非商业转载请注明出处。', 'moe' ); ?>
                </p>
            </div>
        </div>
        
        <style>
        /* 增强版权声明样式 */
        .copyright-notice {
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f4f8 100%);
            border-left: 4px solid #24a5db;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .copyright-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }
        
        .copyright-header i {
            color: #24a5db;
            font-size: 20px;
        }
        
        .copyright-body p {
            margin: 10px 0;
            font-size: 14px;
            line-height: 1.8;
            color: #666;
        }
        
        .copyright-body p i {
            color: #24a5db;
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }
        
        .copyright-body strong {
            color: #333;
        }
        
        .copyright-body a {
            color: #24a5db;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .copyright-body a:hover {
            color: #1a8fbd;
            text-decoration: underline;
        }
        
        .copyright-link a {
            word-break: break-all;
        }
        
        .copyright-notice-text {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
            font-size: 13px !important;
            color: #999 !important;
        }
        
        /* 移动端优化 */
        @media screen and (max-width: 768px) {
            .copyright-notice {
                padding: 15px;
                margin: 20px 0;
            }
            
            .copyright-header {
                font-size: 16px;
            }
            
            .copyright-header i {
                font-size: 18px;
            }
            
            .copyright-body p {
                font-size: 13px;
            }
            
            .copyright-notice-text {
                font-size: 12px !important;
            }
        }
        
        /* 打印样式 */
        @media print {
            .copyright-notice {
                background: white;
                border: 1px solid #000;
                page-break-inside: avoid;
            }
            
            .copyright-body a {
                color: #000;
                text-decoration: underline;
            }
        }
        </style>

        <div class="cutline">
            <span><?php _e( '正文到此结束', 'moe' ); ?></span>
        </div>

        <!-- 文章点赞按钮 -->
        <?php if ( function_exists( 'moe_display_like_button' ) ) : ?>
            <div class="post-actions">
                <?php moe_display_like_button(); ?>
            </div>
        <?php endif; ?>

        <!-- 社交分享按钮 -->
        <?php if ( function_exists( 'moe_social_share' ) ) {
            moe_social_share();
        } ?>

        <!-- 相关文章推荐 -->
        <?php if ( function_exists( 'moe_related_posts' ) ) {
            moe_related_posts();
        } ?>

        <!-- 文章导航（上一篇/下一篇） -->
        <div class="post-navigation">
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            ?>
            
            <?php if ( $prev_post ) : ?>
                <div class="nav-previous">
                    <?php _e( '上一篇：', 'moe' ); ?>
                    <a rel="prev" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="nav-previous">
                    <?php _e( '上一篇: 已经是第一篇了', 'moe' ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $next_post ) : ?>
                <div class="nav-next">
                    <?php _e( '下一篇：', 'moe' ); ?>
                    <a rel="next" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="nav-next">
                    <?php _e( '下一篇: 已经是最后一篇了', 'moe' ); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 评论区域 -->
        <?php
        // 如果开启了评论，加载评论模板
        if ( comments_open() || get_comments_number() ) :
        ?>
            <div class="commt_box">
                <a class="loli" href="#comments">
                    <i class="fa fa-comments-o"></i>
                    <?php _e( '吐槽', 'moe' ); ?>
                    <span><?php echo get_comments_number(); ?><?php _e( '发', 'moe' ); ?></span>
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

