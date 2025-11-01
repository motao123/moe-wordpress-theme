<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- 页面加载动画 -->
<div class="page-loader">
    <div class="loader-spinner"></div>
</div>
<?php wp_body_open(); ?>

<header id="header">
    <!-- 彩色顶部条纹 -->
    <ul class="group" id="color-bars">
        <li id="color-1"></li>
        <li id="color-2"></li>
        <li id="color-3"></li>
        <li id="color-4"></li>
        <li id="color-5"></li>
        <li id="color-6"></li>
    </ul>

    <!-- 顶部导航栏 -->
    <div data-offset-top="294" data-spy="affix" id="topbar" class="affix-top">
        <nav id="nav-topbar" class="nav-container group">
            <!-- 移动端菜单切换按钮 -->
            <div class="nav-toggle">
                <i class="fa fa-bars"></i>
            </div>

            <div class="nav-text"></div>

            <!-- 主导航菜单 - 支持多级菜单 -->
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location'  => 'primary',
                    'container'       => 'div',
                    'container_class' => 'nav-wrap',
                    'container_id'    => 'nav-wrap',
                    'menu_class'      => 'nav container-inner group',
                    'menu_id'         => 'menu-header',
                    'fallback_cb'     => false,
                    'depth'           => 3, // 支持3级菜单
                    'walker'          => new MOE_Walker_Nav_Menu(),
                ) );
            } else {
                // 如果没有设置菜单，显示默认菜单
                ?>
                <div class="nav-wrap" id="nav-wrap">
                    <ul class="nav container-inner group" id="menu-header">
                        <li class="menu-item <?php if ( is_home() ) echo 'current_page_item'; ?>">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( '首页', 'moe' ); ?></a>
                        </li>
                        <?php
                        // 显示所有分类
                        $categories = get_categories( array( 'hide_empty' => true ) );
                        foreach ( $categories as $category ) {
                            $current = ( is_category( $category->term_id ) ) ? 'current_page_item' : 'menu-item';
                            echo '<li class="' . $current . '">';
                            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                            
                            // 获取子分类
                            $child_cats = get_categories( array( 
                                'parent'     => $category->term_id,
                                'hide_empty' => true,
                            ) );
                            
                            if ( ! empty( $child_cats ) ) {
                                echo '<ul class="sub-menu level-0">';
                                foreach ( $child_cats as $child_cat ) {
                                    echo '<li><a href="' . esc_url( get_category_link( $child_cat->term_id ) ) . '">' . esc_html( $child_cat->name ) . '</a></li>';
                                }
                                echo '</ul>';
                            }
                            
                            echo '</li>';
                        }
                        ?>
                        <?php if ( is_user_logged_in() ) : ?>
                            <li class="menu-item">
                                <a href="<?php echo esc_url( admin_url() ); ?>"><?php _e( '管理后台', 'moe' ); ?></a>
                            </li>
                            <li class="menu-item">
                                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php _e( '退出', 'moe' ); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php
            }
            ?>

            <!-- 搜索框 -->
            <div class="group" id="header-search">
                <form method="get" id="nav_search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input 
                        name="s" 
                        id="nav_search_s" 
                        placeholder="<?php esc_attr_e( 'Search', 'moe' ); ?>" 
                        type="text" 
                        value="<?php echo get_search_query(); ?>"
                    />
                </form>
            </div>
        </nav><!-- /#nav-topbar -->
    </div><!-- /#topbar -->

    <!-- 头部个人信息区域 -->
    <div class="container">
        <div class="group" id="head">
            <!-- 头像 -->
            <div id="head-face">
                <?php moe_custom_logo(); ?>
            </div>

            <!-- 社交链接 -->
            <div class="wocao">
                <?php
                $social_links = moe_get_social_links();
                if ( ! empty( $social_links['weibo'] ) && $social_links['weibo'] != '#' ) :
                ?>
                    <a href="<?php echo esc_url( $social_links['weibo'] ); ?>" title="<?php esc_attr_e( '微博', 'moe' ); ?>" class="btn-one" target="_blank" rel="nofollow">
                        <i class="fa fa-weibo"></i>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $social_links['qq'] ) && $social_links['qq'] != '#' ) : ?>
                    <a href="<?php echo esc_url( $social_links['qq'] ); ?>" title="<?php esc_attr_e( 'QQ', 'moe' ); ?>" class="btn-11" target="_blank" rel="nofollow">
                        <i class="fa fa-qq"></i>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $social_links['github'] ) && $social_links['github'] != '#' ) : ?>
                    <a href="<?php echo esc_url( $social_links['github'] ); ?>" title="<?php esc_attr_e( 'GitHub', 'moe' ); ?>" class="btn-22" target="_blank" rel="nofollow">
                        <i class="fa fa-github"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- 站点标题和描述 -->
            <a rel="home" title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <h2 id="head-about"><?php bloginfo( 'name' ); ?></h2>
            </a>
            <h1 id="head-about"><?php bloginfo( 'description' ); ?></h1>
        </div><!-- /#head -->
    </div><!-- /.container -->
</header><!-- /#header -->

<div id="body">
    <div class="container" id="main" role="main">

