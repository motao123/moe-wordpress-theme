<?php
/**
 * 友情链接检测功能
 * Link Checker
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 检查单个链接状态
 */
function moe_check_link_status( $url ) {
    $response = wp_remote_head( $url, array(
        'timeout'     => 10,
        'redirection' => 5,
        'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ) );
    
    if ( is_wp_error( $response ) ) {
        return array(
            'status'  => 'error',
            'message' => $response->get_error_message(),
            'code'    => 0,
        );
    }
    
    $code = wp_remote_retrieve_response_code( $response );
    
    return array(
        'status'  => ( $code >= 200 && $code < 400 ) ? 'success' : 'error',
        'message' => wp_remote_retrieve_response_message( $response ),
        'code'    => $code,
    );
}

/**
 * 检查所有友情链接
 */
function moe_check_all_links() {
    $links = get_bookmarks( array(
        'orderby'        => 'name',
        'order'          => 'ASC',
        'hide_invisible' => 0,
    ) );
    
    $results = array();
    $failed_links = array();
    
    foreach ( $links as $link ) {
        $result = moe_check_link_status( $link->link_url );
        $result['link'] = $link;
        $results[] = $result;
        
        // 更新链接元数据
        update_option( 'moe_link_status_' . $link->link_id, $result );
        
        if ( $result['status'] === 'error' ) {
            $failed_links[] = $link;
        }
    }
    
    // 更新最后检查时间
    update_option( 'moe_last_link_check', current_time( 'mysql' ) );
    
    // 如果有失效链接，发送邮件通知
    if ( ! empty( $failed_links ) ) {
        moe_send_link_check_email( $failed_links );
    }
    
    return $results;
}

/**
 * 发送链接检测邮件通知
 */
function moe_send_link_check_email( $failed_links ) {
    $admin_email = get_option( 'admin_email' );
    $site_name = get_bloginfo( 'name' );
    
    $subject = sprintf( '[%s] 友情链接检测报告 - 发现 %d 个失效链接', $site_name, count( $failed_links ) );
    
    $message = "<html><body>";
    $message .= "<h2>友情链接检测报告</h2>";
    $message .= "<p>以下链接检测失败，请及时处理：</p>";
    $message .= "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    $message .= "<tr><th>链接名称</th><th>链接地址</th><th>错误信息</th><th>状态码</th></tr>";
    
    foreach ( $failed_links as $link ) {
        $status = get_option( 'moe_link_status_' . $link->link_id );
        $message .= sprintf(
            "<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>",
            esc_html( $link->link_name ),
            esc_url( $link->link_url ),
            esc_html( $link->link_url ),
            esc_html( $status['message'] ),
            intval( $status['code'] )
        );
    }
    
    $message .= "</table>";
    $message .= "<p>检测时间：" . current_time( 'Y-m-d H:i:s' ) . "</p>";
    $message .= "<p>请登录后台查看详情：<a href='" . admin_url( 'link-manager.php' ) . "'>友情链接管理</a></p>";
    $message .= "</body></html>";
    
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    
    wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * 定时任务：每天检查一次友情链接
 */
function moe_schedule_link_check() {
    if ( ! wp_next_scheduled( 'moe_daily_link_check' ) ) {
        wp_schedule_event( time(), 'daily', 'moe_daily_link_check' );
    }
}
add_action( 'wp', 'moe_schedule_link_check' );

/**
 * 执行定时链接检查
 */
function moe_do_link_check() {
    moe_check_all_links();
}
add_action( 'moe_daily_link_check', 'moe_do_link_check' );

/**
 * 在后台添加链接状态列
 */
function moe_link_status_column( $columns ) {
    $columns['link_status'] = __( '链接状态', 'moe' );
    return $columns;
}
add_filter( 'manage_link-manager_columns', 'moe_link_status_column' );

/**
 * 显示链接状态
 */
function moe_link_status_column_content( $column_name, $link_id ) {
    if ( 'link_status' === $column_name ) {
        $status = get_option( 'moe_link_status_' . $link_id );
        
        if ( $status ) {
            if ( $status['status'] === 'success' ) {
                echo '<span style="color: green;">✓ ';
                echo __( '正常', 'moe' );
                echo ' (' . $status['code'] . ')</span>';
            } else {
                echo '<span style="color: red;">✗ ';
                echo __( '失效', 'moe' );
                echo ' (' . esc_html( $status['message'] ) . ')</span>';
            }
        } else {
            echo '<span style="color: gray;">- ' . __( '未检测', 'moe' ) . '</span>';
        }
    }
}
add_action( 'manage_link_custom_column', 'moe_link_status_column_content', 10, 2 );

/**
 * 添加手动检查按钮
 */
function moe_add_link_check_button() {
    if ( ! current_user_can( 'manage_links' ) ) {
        return;
    }
    
    $screen = get_current_screen();
    if ( $screen && 'link-manager' === $screen->id ) {
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.wrap h1').after('<a href="<?php echo admin_url( 'admin.php?page=moe-link-checker' ); ?>" class="page-title-action"><?php _e( '检查所有链接', 'moe' ); ?></a>');
        });
        </script>
        <?php
    }
}
add_action( 'admin_footer', 'moe_add_link_check_button' );

/**
 * 添加链接检测管理页面
 */
function moe_add_link_checker_menu() {
    add_submenu_page(
        'link-manager.php',
        __( '友情链接检测', 'moe' ),
        __( '链接检测', 'moe' ),
        'manage_links',
        'moe-link-checker',
        'moe_link_checker_page'
    );
}
add_action( 'admin_menu', 'moe_add_link_checker_menu' );

/**
 * 链接检测管理页面内容
 */
function moe_link_checker_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( '友情链接检测', 'moe' ); ?></h1>
        
        <?php
        // 处理手动检查请求
        if ( isset( $_POST['check_links'] ) && check_admin_referer( 'moe_check_links' ) ) {
            echo '<div class="notice notice-info"><p>' . __( '正在检查链接...', 'moe' ) . '</p></div>';
            $results = moe_check_all_links();
            echo '<div class="notice notice-success"><p>' . __( '链接检查完成！', 'moe' ) . '</p></div>';
        }
        
        $last_check = get_option( 'moe_last_link_check' );
        ?>
        
        <div class="card">
            <h2><?php _e( '检测设置', 'moe' ); ?></h2>
            <p><?php _e( '系统会每天自动检测所有友情链接的可用性。', 'moe' ); ?></p>
            
            <?php if ( $last_check ) : ?>
                <p>
                    <strong><?php _e( '最后检查时间：', 'moe' ); ?></strong>
                    <?php echo $last_check; ?>
                </p>
            <?php endif; ?>
            
            <form method="post">
                <?php wp_nonce_field( 'moe_check_links' ); ?>
                <p>
                    <button type="submit" name="check_links" class="button button-primary">
                        <span class="dashicons dashicons-update"></span>
                        <?php _e( '立即检查所有链接', 'moe' ); ?>
                    </button>
                </p>
            </form>
        </div>
        
        <?php
        // 显示链接状态
        $links = get_bookmarks( array(
            'orderby'        => 'name',
            'order'          => 'ASC',
            'hide_invisible' => 0,
        ) );
        
        if ( ! empty( $links ) ) :
        ?>
            <h2><?php _e( '链接状态', 'moe' ); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e( '链接名称', 'moe' ); ?></th>
                        <th><?php _e( '链接地址', 'moe' ); ?></th>
                        <th><?php _e( '状态', 'moe' ); ?></th>
                        <th><?php _e( '最后检查', 'moe' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $links as $link ) : 
                        $status = get_option( 'moe_link_status_' . $link->link_id );
                    ?>
                        <tr>
                            <td><strong><?php echo esc_html( $link->link_name ); ?></strong></td>
                            <td><a href="<?php echo esc_url( $link->link_url ); ?>" target="_blank"><?php echo esc_html( $link->link_url ); ?></a></td>
                            <td>
                                <?php if ( $status ) : ?>
                                    <?php if ( $status['status'] === 'success' ) : ?>
                                        <span style="color: green;"><span class="dashicons dashicons-yes"></span> <?php _e( '正常', 'moe' ); ?> (<?php echo $status['code']; ?>)</span>
                                    <?php else : ?>
                                        <span style="color: red;"><span class="dashicons dashicons-no"></span> <?php _e( '失效', 'moe' ); ?> (<?php echo esc_html( $status['message'] ); ?>)</span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span style="color: gray;">- <?php _e( '未检测', 'moe' ); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $last_check ? $last_check : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <style>
    .card {
        max-width: 800px;
        padding: 20px;
        margin: 20px 0;
    }
    .wp-list-table {
        margin-top: 20px;
    }
    button .dashicons {
        vertical-align: middle;
        margin-top: -2px;
    }
    </style>
    <?php
}

/**
 * 清理定时任务（主题停用时）
 */
function moe_deactivate_link_checker() {
    wp_clear_scheduled_hook( 'moe_daily_link_check' );
}
register_deactivation_hook( __FILE__, 'moe_deactivate_link_checker' );

