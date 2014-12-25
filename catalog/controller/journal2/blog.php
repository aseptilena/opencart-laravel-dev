<?php

/**
 * @property Journal2 $journal2
 * @property ModelJournal2Blog model_journal2_blog
 */

class ControllerJournal2Blog extends Controller {

    private $blog_title;
    private $blog_heading_title;
    private $blog_meta_title;
    private $blog_meta_description;
    private $blog_meta_keywords;

    protected $data = array();

    protected function render() {
        if (Front::$IS_OC2) {
            return $this->load->view($this->blog_template, $this->data);
        }
        $this->template = $this->blog_template;
        return parent::render();
    }

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('journal2/blog');
        $this->load->model('tool/image');

        $this->language->load('product/product');
        $this->language->load('product/category');

        /* check blog status */
        if (!$this->model_journal2_blog->isEnabled()) {
            $this->redirect('index.php?route=error/not_found');
            exit();
        }

        $this->data['date_format_short']     = $this->language->get('date_format_short');
        $this->data['date_format_long']      = $this->language->get('date_format_long');
        $this->data['time_format']           = $this->language->get('time_format');

        /* blog data */
        $this->blog_title               = $this->journal2->settings->get('config_blog_settings.title.value.' . $this->config->get('config_language_id'), 'Journal Blog');
        $this->blog_heading_title       = $this->journal2->settings->get('config_blog_settings.title.value.' . $this->config->get('config_language_id'), 'Journal Blog');
        $this->blog_meta_title          = $this->journal2->settings->get('config_blog_settings.meta_title.value.' . $this->config->get('config_language_id'));
        $this->blog_meta_description    = $this->journal2->settings->get('config_blog_settings.meta_description.value.' . $this->config->get('config_language_id'));
        $this->blog_meta_keywords       = $this->journal2->settings->get('config_blog_settings.meta_keywords.value.' . $this->config->get('config_language_id'));
    }

    public function index() {
        /* filters */
        $sort = $this->journal2->settings->get('config_blog_settings.posts_sort', 'newest');
        $limit = $this->journal2->settings->get('config_blog_settings.posts_limit', 15);

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        /* general breadcrumbs */
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->blog_title,
            'href'      => $this->url->link('journal2/blog'),
            'separator' => $this->language->get('text_separator')
        );

        if (isset($this->request->get['journal_blog_category_id'])) {
            $category_id = $this->request->get['journal_blog_category_id'];
        } else {
            $category_id = 0;
        }

        if (isset($this->request->get['journal_blog_search'])) {
            $search = $this->request->get['journal_blog_search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['journal_blog_tag'])) {
            $tag = $this->request->get['journal_blog_tag'];
        } else {
            $tag = '';
        }

        $category_info = $this->model_journal2_blog->getCategory($category_id);

        if ($category_info) {
            $url = '';

            if (isset($this->request->get['journal_blog_search'])) {
                $url .= '&journal_blog_search=' . $this->request->get['journal_blog_search'];
            }

            $this->data['breadcrumbs'][] = array(
                'text'      => $category_info['name'],
                'href'      => $this->url->link('journal2/blog', 'journal_blog_category_id=' . $category_id . $url),
                'separator' => $this->language->get('text_separator')
            );

            $this->blog_title           = $category_info['name'];
            $this->blog_heading_title   = $category_info['name'];
        } else if ($tag) {
            $this->blog_heading_title = $this->language->get('text_tags') . ' ' . $tag;
        }

        $this->data['heading_title'] = $this->blog_heading_title;
        $this->document->setTitle($this->blog_title);
        $this->document->setDescription($this->blog_meta_description);
        $this->document->setKeywords($this->blog_meta_keywords);
        $this->journal2->settings->set('blog_meta_title',       $this->blog_meta_title);
        $this->journal2->settings->set('fb_meta_title',         $this->blog_title);
        $this->journal2->settings->set('fb_meta_description',   $this->blog_meta_description);

        $this->data['grid_classes'] = Journal2Utils::getProductGridClasses($this->journal2->settings->get('config_blog_settings.posts_per_row.value'), $this->journal2->settings->get('site_width', 1024), $this->journal2->settings->get('config_columns_count', 0));
        $this->data['posts'] = array();

        $data = array(
            'category_id'   => $category_id,
            'tag'           => $tag,
            'sort'          => $sort,
            'search'        => $search,
            'start'         => ($page - 1) * $limit,
            'limit'         => $limit
        );

        $posts = $this->model_journal2_blog->getPosts($data);
        $posts_total = $this->model_journal2_blog->getPostsTotal($data);

        $image_width    = $this->journal2->settings->get('config_blog_settings.posts_image_width', 250);
        $image_height   = $this->journal2->settings->get('config_blog_settings.posts_image_height', 250);
        $image_type     = $this->journal2->settings->get('config_blog_settings.posts_image_type', 'fit');

        foreach ($posts as $post) {
            $this->data['posts'][] = array(
                'name'          => $post['name'],
                'author'        => $this->model_journal2_blog->getAuthorName($post),
                'comments'      => $post['comments'],
                'date'          => date($this->language->get('date_format_short'), strtotime($post['date'])),
                'image'         => Journal2Utils::resizeImage($this->model_tool_image, $post, $image_width, $image_height, $image_type, 'crop'),
                'href'          => $this->url->link('journal2/blog/post', ($category_info ? 'journal_blog_category_id=' . $category_id . '&' : '') . 'journal_blog_post_id=' . $post['post_id']),
                'description'   => utf8_substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0, $this->journal2->settings->get('config_blog_settings.description_char_limit', 150)) . '...',
            );
        }

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['continue'] = $this->url->link('common/home');

        $url = '';

        if ($category_info) {
            $url .= '&journal_blog_category_id=' . $category_id;
        }

        if ($tag) {
            $url .= '&journal_blog_tag=' . $tag;
        }

        if (isset($this->request->get['journal_blog_search'])) {
            $url .= '&journal_blog_search=' . $this->request->get['journal_blog_search'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $pagination = new Pagination();
        $pagination->total = $posts_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('journal2/blog', $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['limit'] = $limit;

        $this->blog_template = $this->config->get('config_template') . '/template/journal2/blog/posts.tpl';

        if (Front::$IS_OC2) {
            $this->data['column_left'] = $this->load->controller('common/column_left');
            $this->data['column_right'] = $this->load->controller('common/column_right');
            $this->data['content_top'] = $this->load->controller('common/content_top');
            $this->data['content_bottom'] = $this->load->controller('common/content_bottom');
            $this->data['footer'] = $this->load->controller('common/footer');
            $this->data['header'] = $this->load->controller('common/header');
        } else {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );
        }

        $this->response->setOutput($this->render());
    }

    public function post() {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->blog_title,
            'href'      => $this->url->link('journal2/blog'),
            'separator' => $this->language->get('text_separator')
        );

        if (isset($this->request->get['journal_blog_category_id'])) {
            $category_id = $this->request->get['journal_blog_category_id'];
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_journal2_blog->getCategory($category_id);

        if ($category_info) {
            $this->data['breadcrumbs'][] = array(
                'text'      => $category_info['name'],
                'href'      => $this->url->link('journal2/blog', 'journal_blog_category_id=' . $category_id),
                'separator' => $this->language->get('text_separator')
            );
        }

        if (isset($this->request->get['journal_blog_post_id'])) {
            $post_id = $this->request->get['journal_blog_post_id'];
        } else {
            $post_id = 0;
        }

        $post_info = $this->model_journal2_blog->getPost($post_id);

        if ($post_info) {
            $this->data['breadcrumbs'][] = array(
                'text'      => $post_info['name'],
                'href'      => $this->url->link('journal2/blog/post', ($category_info ? 'journal_blog_category_id=' . $category_id . '&' : '') . 'journal_blog_post_id=' . $post_info['post_id']),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['text_tags'] = $this->language->get('text_tags');
            $this->data['tab_related'] = $this->language->get(Front::$IS_OC2 ? 'text_related' : 'tab_related');
            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_wishlist'] = $this->language->get('button_wishlist');
            $this->data['button_compare'] = $this->language->get('button_compare');

            $this->blog_title               = $post_info['name'];
            $this->blog_heading_title       = $post_info['name'];
            $this->blog_meta_title          = $post_info['meta_title'];
            $this->blog_meta_description    = $post_info['meta_description'];
            $this->blog_meta_keywords       = $post_info['meta_keywords'];

            $this->data['post_id'] = $post_info['post_id'];
            $this->data['post_author'] = $this->model_journal2_blog->getAuthorName($post_info);
            $this->data['post_date'] = $post_info['date_created'];
            $this->data['post_content'] = $post_info['description'];
            $this->data['default_author_image'] = Journal2Utils::resizeImage($this->model_tool_image, 'data/journal2/misc/avatar.png', 70, 70);

            $this->data['post_tags'] = array();
            foreach (explode(',', $post_info['tags']) as $tag) {
                $tag = trim($tag);
                if (!$tag) continue;
                $this->data['post_tags'][] = array(
                    'href'  => $this->url->link('journal2/blog', 'journal_blog_tag=' . $tag),
                    'name'  => $tag
                );
            }

            $results = $this->model_journal2_blog->getCategoriesByPostId($post_id);
            $this->data['post_categories'] = array();
            foreach ($results as $result) {
                $this->data['post_categories'][] = array(
                    'href'  => $this->url->link('journal2/blog', 'journal_blog_category_id=' . $result['category_id']),
                    'name'  => $result['name']
                );
            }


            $this->data['grid_classes'] = Journal2Utils::getProductGridClasses($this->journal2->settings->get('config_blog_settings.related_products_per_row.value'), $this->journal2->settings->get('site_width', 1024), $this->journal2->settings->get('config_columns_count', 0));
            $this->data['carousel'] = $this->journal2->settings->get('config_blog_settings.related_products_carousel');

            $this->data['related_products'] = array();
            $results = $this->model_journal2_blog->getRelatedProducts($post_id);

            foreach ($results as $result) {
                $image = Journal2Utils::resizeImage($this->model_tool_image, $result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'), 'fit');

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $this->data['related_products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb'   	 => $image,
                    'name'    	 => $result['name'],
                    'price'   	 => $price,
                    'special' 	 => $special,
                    'rating'     => $rating,
                    'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
                    'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $this->data['allow_comments'] = $this->model_journal2_blog->getCommentsStatus($post_id);
            $this->data['comments'] = $this->model_journal2_blog->getComments($post_id);

            $this->model_journal2_blog->updateViews($post_id);

            $this->data['heading_title'] = $this->blog_heading_title;
            $this->document->setTitle($this->blog_title);
            $this->document->setDescription($this->blog_meta_description);
            $this->document->setKeywords($this->blog_meta_keywords);
            $this->document->addLink($this->url->link('journal2/blog/post', 'journal_blog_post_id=' . $post_id), 'canonical');
            $this->journal2->settings->set('blog_meta_title',       $this->blog_meta_title);
            $this->journal2->settings->set('fb_meta_title',         $this->blog_title);
            $this->journal2->settings->set('fb_meta_description',   trim(utf8_substr(strip_tags(html_entity_decode($post_info['description'], ENT_QUOTES, 'UTF-8')), 0, 300))  . '...');
            $this->journal2->settings->set('fb_meta_image',         Journal2Utils::staticAsset('image/' . $post_info['image']));


            $this->blog_template = $this->config->get('config_template') . '/template/journal2/blog/post.tpl';
        } else {
            $this->language->load('error/not_found');

            $this->document->setTitle($this->language->get('text_error'));
            $this->data['heading_title'] = $this->language->get('text_error');
            $this->data['text_error'] = $this->language->get('text_error');
            $this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->blog_template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->blog_template = 'default/template/error/not_found.tpl';
            }
        }

        if (Front::$IS_OC2) {
            $this->data['column_left'] = $this->load->controller('common/column_left');
            $this->data['column_right'] = $this->load->controller('common/column_right');
            $this->data['content_top'] = $this->load->controller('common/content_top');
            $this->data['content_bottom'] = $this->load->controller('common/content_bottom');
            $this->data['footer'] = $this->load->controller('common/footer');
            $this->data['header'] = $this->load->controller('common/header');
        } else {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );
        }

        $this->response->setOutput($this->render());
    }

    public function comment() {
        if (!$this->model_journal2_blog->getCommentsStatus(Journal2Utils::getProperty($this->request->get, 'post_id'))) {
            $this->response->setOutput(json_encode(array(
                'status'    => 'error',
                'message'   => 'Comments are not allowed on this post!'
            )));
            return;
        }

        $errors = array();

        $name = Journal2Utils::getProperty($this->request->post, 'name', '');
        $email = Journal2Utils::getProperty($this->request->post, 'email', '');
        $website = Journal2Utils::getProperty($this->request->post, 'website', '');
        $comment = Journal2Utils::getProperty($this->request->post, 'comment', '');

        if (!$name) {
            $errors[] = 'name';
        }

        if (!$email || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
            $errors[] = 'email';
        }

        if (!$comment) {
            $errors[] = 'comment';
        }

        if (!$errors) {
            $data = $this->model_journal2_blog->createComment(array(
                'post_id'   => Journal2Utils::getProperty($this->request->get, 'post_id'),
                'parent_id' => Journal2Utils::getProperty($this->request->post, 'parent_id'),
                'name'      => $name,
                'email'     => $email,
                'website'   => $website,
                'comment'   => $comment
            ));

            if ($this->journal2->settings->get('config_blog_settings.auto_approve_comments', '1') === '1') {
                $data['time'] = date($this->language->get('time_format'), strtotime($data['date']));
                $data['date'] = date($this->language->get('date_format_short'), strtotime($data['date']));
                $data['avatar'] = Journal2Utils::gravatar($data['email'], '', 70);

                $this->response->setOutput(json_encode(array(
                    'status'    => 'success',
                    'data'      => $data,
                    'message'   => $this->journal2->settings->get('blog_form_comment_submitted', 'Comment submitted.')
                )));
            } else {
                $this->response->setOutput(json_encode(array(
                    'status'    => 'success',
                    'message'   => $this->journal2->settings->get('blog_form_comment_awaiting_approval', 'Comment awaiting approval.')
                )));
            }
        } else {
            $this->response->setOutput(json_encode(array(
                'status'    => 'error',
                'errors'    => $errors
            )));
        }
    }

}
