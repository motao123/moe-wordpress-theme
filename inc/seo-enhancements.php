<?php
/**
 * MOE主题 SEO 增强功能
 * SEO Enhancements
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加 Open Graph 标签
 */
function moe_add_open_graph_tags() {
    if ( is_singular() ) {
        global $post;
        setup_postdata( $post );
        
        $og_title = get_the_title();
        $og_type = 'article';
        $og_url = get_permalink();
        
        // 获取 Open Graph 图片（优先级：特色图片 > 自定义 Logo > 默认图片）
        if ( has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        } elseif ( has_custom_logo() ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $og_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        } else {
            // 检查默认图片是否存在
            $default_image_path = get_template_directory() . '/images/default.jpg';
            if ( file_exists( $default_image_path ) ) {
                $og_image = get_template_directory_uri() . '/images/default.jpg';
            } else {
                // 使用站点图标作为最后的备选
                $og_image = get_site_icon_url( 512 );
                if ( ! $og_image ) {
                    $og_image = get_bloginfo( 'url' ) . '/favicon.ico';
                }
            }
        }
        
        $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words( strip_tags( get_the_content() ), 30 );
        $og_site_name = get_bloginfo( 'name' );
        
        ?>
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>" />
        <meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
        <meta property="og:url" content="<?php echo esc_url( $og_url ); ?>" />
        <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>" />
        <meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>" />
        <meta property="og:site_name" content="<?php echo esc_attr( $og_site_name ); ?>" />
        <meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>" />
        
        <?php if ( is_single() ) : ?>
        <meta property="article:published_time" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" />
        <meta property="article:modified_time" content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>" />
        <meta property="article:author" content="<?php echo esc_attr( get_the_author() ); ?>" />
        <?php
        $categories = get_the_category();
        if ( $categories ) {
            foreach ( $categories as $category ) {
                echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
            }
        }
        
        $tags = get_the_tags();
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
            }
        }
        ?>
        <?php endif; ?>
        <?php
        
        wp_reset_postdata();
    } elseif ( is_home() || is_front_page() ) {
        ?>
        <!-- Open Graph Meta Tags for Homepage -->
        <meta property="og:title" content="<?php bloginfo( 'name' ); ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>" />
        <meta property="og:image" content="<?php echo esc_url( get_template_directory_uri() . '/images/default.jpg' ); ?>" />
        <meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />
        <meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>" />
        <?php
    }
}
add_action( 'wp_head', 'moe_add_open_graph_tags' );

// Twitter Cards 功能已移除（根据用户需求）

/**
 * 添加 Schema.org 结构化数据（JSON-LD）
 */
function moe_add_schema_markup() {
    if ( is_single() ) {
        global $post;
        setup_postdata( $post );
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'BlogPosting',
            'headline' => get_the_title(),
            'image'    => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '',
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author' => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
                'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => has_custom_logo() ? wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) : get_template_directory_uri() . '/images/avatar.jpg',
                ),
            ),
            'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words( strip_tags( get_the_content() ), 30 ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
        );
        
        // 添加分类
        $categories = get_the_category();
        if ( $categories ) {
            $schema['articleSection'] = $categories[0]->name;
        }
        
        // 添加标签
        $tags = get_the_tags();
        if ( $tags ) {
            $keywords = array();
            foreach ( $tags as $tag ) {
                $keywords[] = $tag->name;
            }
            $schema['keywords'] = implode( ', ', $keywords );
        }
        
        // 添加字数统计
        $word_count = str_word_count( strip_tags( get_the_content() ) );
        if ( $word_count > 0 ) {
            $schema['wordCount'] = $word_count;
        }
        
        ?>
        <!-- Schema.org JSON-LD -->
        <script type="application/ld+json">
        <?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
        </script>
        <?php
        
        wp_reset_postdata();
    } elseif ( is_home() || is_front_page() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
            'description' => get_bloginfo( 'description' ),
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        ?>
        <!-- Schema.org JSON-LD for Homepage -->
        <script type="application/ld+json">
        <?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
        </script>
        <?php
    } elseif ( is_author() ) {
        $author_id = get_queried_object_id();
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'ProfilePage',
            'mainEntity' => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $author_id ),
                'description' => get_the_author_meta( 'description', $author_id ),
                'url'   => get_author_posts_url( $author_id ),
                'image' => get_avatar_url( get_the_author_meta( 'email', $author_id ) ),
            ),
        );
        
        ?>
        <!-- Schema.org JSON-LD for Author Page -->
        <script type="application/ld+json">
        <?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
        </script>
        <?php
    }
}
add_action( 'wp_head', 'moe_add_schema_markup' );

/**
 * 添加面包屑 Schema.org 标记
 */
function moe_breadcrumb_schema() {
    if ( is_singular() && ! is_front_page() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array(),
        );
        
        // 首页
        $schema['itemListElement'][] = array(
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => __( '首页', 'moe' ),
            'item'     => home_url( '/' ),
        );
        
        $position = 2;
        
        // 分类
        if ( is_single() ) {
            $categories = get_the_category();
            if ( $categories ) {
                $schema['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => $position++,
                    'name'     => $categories[0]->name,
                    'item'     => get_category_link( $categories[0]->term_id ),
                );
            }
        }
        
        // 当前页面
        $schema['itemListElement'][] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
        
        ?>
        <!-- Breadcrumb Schema.org -->
        <script type="application/ld+json">
        <?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
        </script>
        <?php
    }
}
add_action( 'wp_head', 'moe_breadcrumb_schema' );

// Twitter 设置已移除（根据用户需求）

/**
 * 添加元描述标签（如果没有使用 SEO 插件）
 */
function moe_add_meta_description() {
    // 如果安装了 Yoast SEO 或其他 SEO 插件，不输出
    if ( defined( 'WPSEO_VERSION' ) || function_exists( 'rank_math' ) ) {
        return;
    }
    
    $description = '';
    
    if ( is_singular() ) {
        if ( has_excerpt() ) {
            $description = get_the_excerpt();
        } else {
            $description = wp_trim_words( strip_tags( get_the_content() ), 30 );
        }
    } elseif ( is_home() || is_front_page() ) {
        $description = get_bloginfo( 'description' );
    } elseif ( is_category() ) {
        $description = category_description();
    } elseif ( is_tag() ) {
        $description = tag_description();
    } elseif ( is_author() ) {
        $description = get_the_author_meta( 'description' );
    }
    
    if ( $description ) {
        echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'moe_add_meta_description', 1 );

// 元关键词标签已移除（现代 SEO 不再使用）


