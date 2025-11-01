<?php
/**
 * The template for displaying comments
 * WordPress 标准评论系统
 * 
 * @package MOE
 * @since 1.0
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <div class="comm_charu"></div>
        
        <h3 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ( '1' === $comments_number ) {
                printf( _x( '1 条评论', 'comments title', 'moe' ) );
            } else {
                printf(
                    _nx(
                        '%1$s 条评论',
                        '%1$s 条评论',
                        $comments_number,
                        'comments title',
                        'moe'
                    ),
                    number_format_i18n( $comments_number )
                );
            }
            ?>
        </h3>

        <div class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'div',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'moe_comment_list',
                'max_depth'   => 3, // 最多支持3层嵌套评论
            ) );
            ?>
        </div><!-- .comment-list -->

        <?php
        // 评论分页
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
        ?>
            <nav class="comment-navigation" role="navigation">
                <div class="nav-previous">
                    <?php previous_comments_link( __( '<i class="fa fa-arrow-circle-o-left"></i> 较早的评论', 'moe' ) ); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link( __( '较新的评论 <i class="fa fa-arrow-circle-o-right"></i>', 'moe' ) ); ?>
                </div>
            </nav>
        <?php endif; ?>

        <?php if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="no-comments"><?php _e( '评论已关闭。', 'moe' ); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments() ?>

    <?php
    // 评论表单
    if ( comments_open() ) :
        
        // 自定义评论表单字段
        $comment_fields = array(
            'author' => '<p class="comment-form-author">' .
                        '<label for="author">' . __( '名字', 'moe' ) . ( get_option( 'require_name_email' ) ? ' <span class="required">*</span>' : '' ) . '</label>' .
                        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245" ' . ( get_option( 'require_name_email' ) ? 'required' : '' ) . ' />' .
                        '</p>',
            
            'email'  => '<p class="comment-form-email">' .
                        '<label for="email">' . __( '邮箱', 'moe' ) . ( get_option( 'require_name_email' ) ? ' <span class="required">*</span>' : '' ) . '</label>' .
                        '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes" ' . ( get_option( 'require_name_email' ) ? 'required' : '' ) . ' />' .
                        '</p>',
            
            'url'    => '<p class="comment-form-url">' .
                        '<label for="url">' . __( '网址', 'moe' ) . '</label>' .
                        '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />' .
                        '</p>',
        );

        // 评论表单参数
        $comment_form_args = array(
            'title_reply'          => __( '发表评论', 'moe' ),
            'title_reply_to'       => __( '回复 %s', 'moe' ),
            'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
            'title_reply_after'    => '</h3>',
            'cancel_reply_before'  => '<span class="cancel-reply">',
            'cancel_reply_after'   => '</span>',
            'cancel_reply_link'    => __( '取消回复', 'moe' ),
            'label_submit'         => __( '发射(●\'◡\'●)ﾉ♥', 'moe' ),
            'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
            'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
            'format'               => 'html5',
            'comment_field'        => '<p class="comment-form-comment">' .
                                      '<label for="comment">' . __( '评论内容', 'moe' ) . ' <span class="required">*</span></label>' .
                                      '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required placeholder="' . esc_attr__( '既然来了说点什么吧…', 'moe' ) . '"></textarea>' .
                                      '</p>',
            'must_log_in'          => '<p class="must-log-in">' .
                                      sprintf(
                                          __( '您必须 <a href="%s">登录</a> 才能发表评论。', 'moe' ),
                                          wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
                                      ) . '</p>',
            'logged_in_as'         => '<p class="logged-in-as">' .
                                      sprintf(
                                          __( '以 <a href="%1$s">%2$s</a> 身份登录。<a href="%3$s" title="退出此账号">退出?</a>', 'moe' ),
                                          admin_url( 'profile.php' ),
                                          $user_identity,
                                          wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) )
                                      ) . '</p>',
            'comment_notes_before' => '<p class="comment-notes">' .
                                      __( '您的电子邮箱地址不会被公开。', 'moe' ) .
                                      ( get_option( 'require_name_email' ) ? ' ' . __( '必填项已用 <span class="required">*</span> 标注', 'moe' ) : '' ) .
                                      '</p>',
            'comment_notes_after'  => '',
            'fields'               => $comment_fields,
            'class_container'      => 'comment-respond',
            'class_form'           => 'comment-form',
            'class_submit'         => 'submit button',
        );

        comment_form( $comment_form_args );
    endif;
    ?>

</div><!-- #comments -->

<style>
/* 评论表情和工具栏样式 */
.comment-form {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 8px;
    margin-top: 20px;
}

.comment-form-comment textarea {
    width: 100%;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    line-height: 1.6;
    transition: all 0.3s ease;
}

.comment-form-comment textarea:focus {
    outline: none;
    border-color: #24a5db;
    box-shadow: 0 0 5px rgba(36, 165, 219, 0.3);
}

.comment-form-author input,
.comment-form-email input,
.comment-form-url input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.comment-form-author input:focus,
.comment-form-email input:focus,
.comment-form-url input:focus {
    outline: none;
    border-color: #24a5db;
    box-shadow: 0 0 5px rgba(36, 165, 219, 0.3);
}

.comment-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.comment-form .required {
    color: #e74c3c;
}

.comment-form .form-submit {
    margin-top: 20px;
}

.comment-form .submit {
    background: #24a5db;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.comment-form .submit:hover {
    background: #1a8fbd;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.comment-notes {
    font-size: 13px;
    color: #999;
    margin-bottom: 15px;
}

.logged-in-as {
    font-size: 14px;
    margin-bottom: 15px;
}

.logged-in-as a {
    color: #24a5db;
}

.cancel-reply {
    margin-left: 15px;
}

.cancel-reply a {
    color: #e74c3c;
    font-size: 14px;
}

/* 评论列表样式增强 */
.comments-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #24a5db;
}

.comment-children {
    margin-left: 60px;
    margin-top: 15px;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    border-left: 3px solid #24a5db;
}

.comment-navigation {
    margin: 30px 0;
    padding: 20px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.comment-navigation .nav-previous,
.comment-navigation .nav-next {
    display: inline-block;
    margin-right: 20px;
}

.comment-navigation a {
    color: #24a5db;
    transition: all 0.3s ease;
}

.comment-navigation a:hover {
    color: #1a8fbd;
}

.no-comments {
    text-align: center;
    padding: 30px;
    background: #f9f9f9;
    border-radius: 8px;
    color: #999;
    font-size: 14px;
}

/* 响应式优化 */
@media screen and (max-width: 768px) {
    .comment-children {
        margin-left: 20px;
    }
    
    .comment-form {
        padding: 20px;
    }
    
    .comment .avatar {
        float: none;
        margin: 0 0 10px 0;
    }
}

@media screen and (max-width: 480px) {
    .comment-children {
        margin-left: 10px;
        padding: 10px;
    }
}
</style>

