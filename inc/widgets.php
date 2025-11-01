<?php
/**
 * 自定义小工具
 * Custom Widgets
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 热门文章小工具
 */
class MOE_Popular_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_popular_posts',
            __( 'MOE - 热门文章', 'moe' ),
            array( 'description' => __( '显示热门文章列表', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        
        $popular_posts = new WP_Query( array(
            'posts_per_page'      => $number,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => 'meta_value_num',
            'meta_key'            => 'post_views_count',
            'order'               => 'DESC'
        ) );
        
        if ( $popular_posts->have_posts() ) {
            echo '<ul id="hotlog">';
            while ( $popular_posts->have_posts() ) {
                $popular_posts->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '热门文章', 'moe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示数量：', 'moe' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        return $instance;
    }
}

/**
 * 随机文章小工具
 */
class MOE_Random_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_random_posts',
            __( 'MOE - 随机文章', 'moe' ),
            array( 'description' => __( '显示随机文章列表', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        
        $random_posts = new WP_Query( array(
            'posts_per_page'      => $number,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => 'rand'
        ) );
        
        if ( $random_posts->have_posts() ) {
            echo '<ul id="randlog">';
            while ( $random_posts->have_posts() ) {
                $random_posts->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '随机文章', 'moe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( '显示数量：', 'moe' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        return $instance;
    }
}

/**
 * 标签云小工具（增强版）
 */
class MOE_Tag_Cloud_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_tag_cloud',
            __( 'MOE - 标签云', 'moe' ),
            array( 'description' => __( '显示标签云（带文章数量）', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $tags = get_tags( array(
            'orderby' => 'count',
            'order'   => 'DESC',
            'number'  => 30
        ) );
        
        if ( $tags ) {
            // 计算字体大小
            $min_count = 999999;
            $max_count = 0;
            foreach ( $tags as $tag ) {
                if ( $tag->count < $min_count ) $min_count = $tag->count;
                if ( $tag->count > $max_count ) $max_count = $tag->count;
            }
            
            echo '<ul id="blogtags">';
            foreach ( $tags as $tag ) {
                // 根据文章数量计算字体大小 (12pt - 22pt)
                if ( $max_count > $min_count ) {
                    $font_size = 12 + ( ( $tag->count - $min_count ) / ( $max_count - $min_count ) ) * 10;
                } else {
                    $font_size = 16;
                }
                
                echo '<span style="font-size:' . $font_size . 'pt; line-height:30px;">';
                echo '<a href="' . get_tag_link( $tag->term_id ) . '" title="' . $tag->count . ' 篇文章">' . $tag->name . '</a>';
                echo '</span> ';
            }
            echo '</ul>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '标签云', 'moe' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * 分类目录小工具（增强版，支持子分类）
 */
class MOE_Categories_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_categories',
            __( 'MOE - 分类目录', 'moe' ),
            array( 'description' => __( '显示分类目录（支持子分类和文章数）', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $categories = get_categories( array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'parent'     => 0
        ) );
        
        if ( $categories ) {
            echo '<ul id="blogsort">';
            foreach ( $categories as $category ) {
                echo '<li>';
                echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . ' (' . $category->count . ')</a>';
                
                // 获取子分类
                $children = get_categories( array(
                    'parent'     => $category->term_id,
                    'hide_empty' => 1
                ) );
                
                if ( $children ) {
                    echo '<ul>';
                    foreach ( $children as $child ) {
                        echo '<li><a href="' . get_category_link( $child->term_id ) . '">' . $child->name . ' (' . $child->count . ')</a></li>';
                    }
                    echo '</ul>';
                }
                
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '分类目录', 'moe' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * 友情链接小工具
 */
class MOE_Links_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_links',
            __( 'MOE - 友情链接', 'moe' ),
            array( 'description' => __( '显示友情链接（使用 Link Manager 插件）', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        // 检查是否有 Link Manager 插件
        $bookmarks = get_bookmarks( array(
            'orderby'        => 'name',
            'order'          => 'ASC',
            'limit'          => -1,
            'category'       => '',
            'hide_invisible' => 1,
            'show_updated'   => 0
        ) );
        
        if ( $bookmarks ) {
            echo '<ul id="link">';
            foreach ( $bookmarks as $bookmark ) {
                echo '<li>';
                echo '<a href="' . esc_url( $bookmark->link_url ) . '" title="' . esc_attr( $bookmark->link_description ) . '" target="_blank" rel="nofollow">' . esc_html( $bookmark->link_name ) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __( '暂无友情链接。请安装 Link Manager 插件并添加链接。', 'moe' ) . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '友情链接', 'moe' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p class="description">
            <?php _e( '需要安装 Link Manager 插件才能管理友情链接。', 'moe' ); ?>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * 博主信息小工具
 */
class MOE_Blogger_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'moe_blogger',
            __( 'MOE - 博主信息', 'moe' ),
            array( 'description' => __( '显示博主个人信息', 'moe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $user_id = get_option( 'moe_blogger_user_id', 1 );
        $user = get_userdata( $user_id );
        
        if ( $user ) {
            echo '<ul id="bloggerinfo">';
            echo '<div id="bloggerinfoimg">';
            echo get_avatar( $user_id, 80 );
            echo '</div>';
            echo '<p><b>';
            if ( $user->user_email ) {
                echo '<a href="mailto:' . esc_attr( $user->user_email ) . '">' . esc_html( $user->display_name ) . '</a>';
            } else {
                echo esc_html( $user->display_name );
            }
            echo '</b><br>';
            echo esc_html( $user->description );
            echo '</p>';
            echo '</ul>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '博主', 'moe' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( '标题：', 'moe' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p class="description">
            <?php _e( '显示用户ID为1的用户信息。可以在"用户"→"您的个人资料"中修改头像和描述。', 'moe' ); ?>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

/**
 * 注册所有自定义小工具
 */
function moe_register_widgets() {
    register_widget( 'MOE_Popular_Posts_Widget' );
    register_widget( 'MOE_Random_Posts_Widget' );
    register_widget( 'MOE_Tag_Cloud_Widget' );
    register_widget( 'MOE_Categories_Widget' );
    register_widget( 'MOE_Links_Widget' );
    register_widget( 'MOE_Blogger_Widget' );
}
add_action( 'widgets_init', 'moe_register_widgets' );

