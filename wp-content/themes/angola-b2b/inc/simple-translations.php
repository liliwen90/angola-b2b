<?php
/**
 * Simple Translation Helper for Polylang Free
 * 简单翻译助手（适用于Polylang Free版本）
 * 
 * Polylang Free版本不支持字符串翻译数据库，
 * 因此我们使用简单的PHP数组来管理所有翻译
 *
 * @package Angola_B2B
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 翻译数据库 - 所有UI文本的4语言翻译
 * 格式: 'key' => ['en' => '英语', 'pt' => '葡萄牙语', 'zh' => '简体中文', 'zh-tw' => '繁体中文']
 */
function angola_b2b_get_translations() {
    return array(
        // ========== HEADER ==========
        'skip_to_content' => array('en' => 'Skip to content', 'pt' => 'Pular para o conteúdo', 'zh' => '跳到内容', 'zh-tw' => '跳到內容'),
        'request_quote' => array('en' => 'Request Quote', 'pt' => 'Solicitar Orçamento', 'zh' => '立即询价', 'zh-tw' => '立即詢價'),
        'select_language' => array('en' => 'Select Language', 'pt' => 'Selecionar Idioma', 'zh' => '选择语言', 'zh-tw' => '選擇語言'),
        'toggle_menu' => array('en' => 'Toggle navigation menu', 'pt' => 'Alternar menu', 'zh' => '切换菜单', 'zh-tw' => '切換選單'),
        'menu' => array('en' => 'Menu', 'pt' => 'Menu', 'zh' => '菜单', 'zh-tw' => '選單'),
        
        // ========== FOOTER ==========
        'company' => array('en' => 'Company', 'pt' => 'Empresa', 'zh' => '公司', 'zh-tw' => '公司'),
        'about_us' => array('en' => 'About Us', 'pt' => 'Sobre Nós', 'zh' => '关于我们', 'zh-tw' => '關於我們'),
        'our_services' => array('en' => 'Our Services', 'pt' => 'Nossos Serviços', 'zh' => '我们的服务', 'zh-tw' => '我們的服務'),
        'careers' => array('en' => 'Careers', 'pt' => 'Carreiras', 'zh' => '招聘', 'zh-tw' => '招聘'),
        'contact' => array('en' => 'Contact', 'pt' => 'Contato', 'zh' => '联系', 'zh-tw' => '聯繫'),
        'products' => array('en' => 'Products', 'pt' => 'Produtos', 'zh' => '产品', 'zh-tw' => '產品'),
        'contact_us' => array('en' => 'Contact Us', 'pt' => 'Fale Conosco', 'zh' => '联系我们', 'zh-tw' => '聯繫我們'),
        'email' => array('en' => 'Email', 'pt' => 'E-mail', 'zh' => '邮箱', 'zh-tw' => '郵箱'),
        'phone' => array('en' => 'Phone', 'pt' => 'Telefone', 'zh' => '电话', 'zh-tw' => '電話'),
        'address' => array('en' => 'Address', 'pt' => 'Endereço', 'zh' => '地址', 'zh-tw' => '地址'),
        'all_rights_reserved' => array('en' => 'All rights reserved.', 'pt' => 'Todos os direitos reservados.', 'zh' => '版权所有。', 'zh-tw' => '版權所有。'),
        'privacy_policy' => array('en' => 'Privacy Policy', 'pt' => 'Política de Privacidade', 'zh' => '隐私政策', 'zh-tw' => '隱私政策'),
        'terms_conditions' => array('en' => 'Terms & Conditions', 'pt' => 'Termos e Condições', 'zh' => '条款与条件', 'zh-tw' => '條款與條件'),
        'back_to_top' => array('en' => 'Back to top', 'pt' => 'Voltar ao topo', 'zh' => '返回顶部', 'zh-tw' => '返回頂部'),
        'footer_description' => array('en' => 'Your trusted partner for quality products and reliable service in construction, agriculture, and industrial equipment.', 'pt' => 'Seu parceiro confiável para produtos de qualidade e serviço confiável em construção, agricultura e equipamentos industriais.', 'zh' => '您值得信赖的合作伙伴，提供优质产品和可靠服务，涵盖建筑、农业和工业设备。', 'zh-tw' => '您值得信賴的合作夥伴，提供優質產品和可靠服務，涵蓋建築、農業和工業設備。'),
        
        // ========== PRODUCT CARD ==========
        'no_image' => array('en' => 'No Image', 'pt' => 'Sem Imagem', 'zh' => '暂无图片', 'zh-tw' => '暫無圖片'),
        'featured' => array('en' => 'Featured', 'pt' => 'Destaque', 'zh' => '推荐', 'zh-tw' => '推薦'),
        'view_details' => array('en' => 'View Details', 'pt' => 'Ver Detalhes', 'zh' => '查看详情', 'zh-tw' => '查看詳情'),
        'inquire_now' => array('en' => 'Inquire Now', 'pt' => 'Consultar Agora', 'zh' => '立即询价', 'zh-tw' => '立即詢價'),
        'in_stock' => array('en' => 'In Stock', 'pt' => 'Em Estoque', 'zh' => '现货', 'zh-tw' => '現貨'),
        'view_more' => array('en' => 'View More', 'pt' => 'Ver Mais', 'zh' => '查看更多', 'zh-tw' => '查看更多'),
        'stock_quantity' => array('en' => 'Stock: %d units', 'pt' => 'Estoque: %d unidades', 'zh' => '库存：%d 件', 'zh-tw' => '庫存：%d 件'),
        
        // ========== HOMEPAGE ==========
        'stock_products_title' => array('en' => 'In Stock - Ship Immediately', 'pt' => 'Em Estoque - Envio Imediato', 'zh' => '现货供应 - 即刻发货', 'zh-tw' => '現貨供應 - 即刻發貨'),
        'stock_products_subtitle' => array('en' => 'Local Stock, Immediate Shipping', 'pt' => 'Estoque Local, Envio Imediato', 'zh' => '本地库存，即刻发货', 'zh-tw' => '本地庫存，即刻發貨'),
        'featured_products' => array('en' => 'Featured Products', 'pt' => 'Produtos em Destaque', 'zh' => '精选产品', 'zh-tw' => '精選產品'),
        'view_all_products' => array('en' => 'View All Products', 'pt' => 'Ver Todos os Produtos', 'zh' => '查看全部产品', 'zh-tw' => '查看全部產品'),
        'no_featured_products' => array('en' => 'No featured products at the moment.', 'pt' => 'Nenhum produto em destaque no momento.', 'zh' => '暂无精选产品。', 'zh-tw' => '暫無精選產品。'),
        'why_choose_us' => array('en' => 'Why Choose Us', 'pt' => 'Por Que Nos Escolher', 'zh' => '为什么选择我们', 'zh-tw' => '為什麼選擇我們'),
        'contact_us_now' => array('en' => 'Contact Us Now', 'pt' => 'Entre em Contato Agora', 'zh' => '立即联系我们', 'zh-tw' => '立即聯繫我們'),
        
        // ========== CATEGORIES (5大分类) ==========
        'logistics_customs' => array('en' => 'Logistics & Customs', 'pt' => 'Logística e Alfândega', 'zh' => '物流清关', 'zh-tw' => '物流清關'),
        'logistics_customs_desc' => array('en' => 'International shipping, customs clearance, warehousing and distribution, supply chain management', 'pt' => 'Transporte internacional, desembaraço aduaneiro, armazenagem e distribuição, gestão da cadeia de suprimentos', 'zh' => '国际运输、清关服务、仓储配送、供应链管理', 'zh-tw' => '國際運輸、清關服務、倉儲配送、供應鏈管理'),
        
        'building_materials' => array('en' => 'Building Materials', 'pt' => 'Materiais de Construção', 'zh' => '建筑材料', 'zh-tw' => '建築材料'),
        'building_materials_desc' => array('en' => 'Steel, cement, wood, decorative materials', 'pt' => 'Aço, cimento, madeira, materiais decorativos', 'zh' => '钢材、水泥、木材、装饰材料', 'zh-tw' => '鋼材、水泥、木材、裝飾材料'),
        
        'agricultural_machinery' => array('en' => 'Agricultural Machinery', 'pt' => 'Máquinas Agrícolas', 'zh' => '农机农具', 'zh-tw' => '農機農具'),
        'agricultural_machinery_desc' => array('en' => 'Power machinery, planting equipment, harvesting equipment, irrigation equipment', 'pt' => 'Máquinas de potência, equipamentos de plantio, equipamentos de colheita, equipamentos de irrigação', 'zh' => '动力机械、播种设备、收获设备、灌溉设备', 'zh-tw' => '動力機械、播種設備、收穫設備、灌溉設備'),
        
        'industrial_equipment' => array('en' => 'Industrial Equipment', 'pt' => 'Equipamentos Industriais', 'zh' => '工业设备', 'zh-tw' => '工業設備'),
        'industrial_equipment_desc' => array('en' => 'Processing equipment, power equipment, automation equipment, testing equipment', 'pt' => 'Equipamentos de processamento, equipamentos de energia, equipamentos de automação, equipamentos de teste', 'zh' => '加工设备、电力设备、自动化设备、检测设备', 'zh-tw' => '加工設備、電力設備、自動化設備、檢測設備'),
        
        'construction_engineering' => array('en' => 'Construction Engineering', 'pt' => 'Engenharia de Construção', 'zh' => '建筑工程', 'zh-tw' => '建築工程'),
        'construction_engineering_desc' => array('en' => 'Earthmoving equipment, concrete equipment, scaffolding systems, lifting equipment', 'pt' => 'Equipamentos de terraplenagem, equipamentos de concreto, sistemas de andaimes, equipamentos de elevação', 'zh' => '土方设备、混凝土设备、脚手架系统、起重设备', 'zh-tw' => '土方設備、混凝土設備、鷹架系統、起重設備'),
        
        // ========== HOMEPAGE ==========
        'our_products' => array('en' => 'Our Products', 'pt' => 'Nossos Produtos', 'zh' => '我们的产品', 'zh-tw' => '我們的產品'),
        'our_products_intro' => array('en' => 'We specialize in sourcing premium quality products from China and delivering them to Angola. Our extensive product range covers construction, agriculture, and industrial sectors, providing reliable solutions for your business needs.', 'pt' => 'Somos especializados em obter produtos de alta qualidade da China e entregá-los em Angola. Nossa ampla gama de produtos abrange os setores de construção, agricultura e indústria, fornecendo soluções confiáveis para suas necessidades comerciais.', 'zh' => '我们专注于从中国采购优质产品并将其交付到安哥拉。我们广泛的产品范围涵盖建筑、农业和工业领域，为您的业务需求提供可靠的解决方案。', 'zh-tw' => '我們專注於從中國採購優質產品並將其交付到安哥拉。我們廣泛的產品範圍涵蓋建築、農業和工業領域，為您的業務需求提供可靠的解決方案。'),
        
        // ========== PRODUCT ARCHIVE ==========
        'no_products_category' => array('en' => 'No products found in this category.', 'pt' => 'Nenhum produto encontrado nesta categoria.', 'zh' => '此分类下暂无产品。', 'zh-tw' => '此分類下暫無產品。'),
        'no_products' => array('en' => 'No products found.', 'pt' => 'Nenhum produto encontrado.', 'zh' => '未找到产品。', 'zh-tw' => '未找到產品。'),
        'add_new_product' => array('en' => 'Add New Product', 'pt' => 'Adicionar Novo Produto', 'zh' => '添加新产品', 'zh-tw' => '添加新產品'),
        'previous' => array('en' => 'Previous', 'pt' => 'Anterior', 'zh' => '上一页', 'zh-tw' => '上一頁'),
        'next' => array('en' => 'Next', 'pt' => 'Próximo', 'zh' => '下一页', 'zh-tw' => '下一頁'),
        
        // ========== PRODUCT SINGLE ==========
        'description' => array('en' => 'Description', 'pt' => 'Descrição', 'zh' => '产品描述', 'zh-tw' => '產品描述'),
        'specifications' => array('en' => 'Specifications', 'pt' => 'Especificações', 'zh' => '规格参数', 'zh-tw' => '規格參數'),
        'certifications' => array('en' => 'Certifications', 'pt' => 'Certificações', 'zh' => '资质认证', 'zh-tw' => '資質認證'),
        'customer_cases' => array('en' => 'Customer Cases', 'pt' => 'Casos de Clientes', 'zh' => '客户案例', 'zh-tw' => '客戶案例'),
        'inquiry' => array('en' => 'Inquiry', 'pt' => 'Consulta', 'zh' => '询价', 'zh-tw' => '詢價'),
        'specification' => array('en' => 'Specification', 'pt' => 'Especificação', 'zh' => '规格', 'zh-tw' => '規格'),
        'value' => array('en' => 'Value', 'pt' => 'Valor', 'zh' => '值', 'zh-tw' => '值'),
        'no_specifications' => array('en' => 'No specifications available for this product.', 'pt' => 'Nenhuma especificação disponível para este produto.', 'zh' => '此产品暂无规格信息。', 'zh-tw' => '此產品暫無規格資訊。'),
        'why_choose_this_product' => array('en' => 'Why Choose This Product', 'pt' => 'Por Que Escolher Este Produto', 'zh' => '为什么选择这款产品', 'zh-tw' => '為什麼選擇這款產品'),
        'feature' => array('en' => 'Feature', 'pt' => 'Característica', 'zh' => '特性', 'zh-tw' => '特性'),
        'our_product' => array('en' => 'Our Product', 'pt' => 'Nosso Produto', 'zh' => '我们的产品', 'zh-tw' => '我們的產品'),
        'regular_product' => array('en' => 'Regular Product', 'pt' => 'Produto Regular', 'zh' => '普通产品', 'zh-tw' => '普通產品'),
        'technical_documents' => array('en' => 'Technical Documents', 'pt' => 'Documentos Técnicos', 'zh' => '技术文档', 'zh-tw' => '技術文檔'),
        'download_technical_specs' => array('en' => 'Download Technical Specifications', 'pt' => 'Baixar Especificações Técnicas', 'zh' => '下载技术规格', 'zh-tw' => '下載技術規格'),
        'watch_video' => array('en' => 'Watch Video', 'pt' => 'Assistir Vídeo', 'zh' => '观看视频', 'zh-tw' => '觀看影片'),
        'related_products' => array('en' => 'Related Products', 'pt' => 'Produtos Relacionados', 'zh' => '相关产品', 'zh-tw' => '相關產品'),
        'add_to_inquiry' => array('en' => 'Add to Inquiry', 'pt' => 'Adicionar à Consulta', 'zh' => '添加询价', 'zh-tw' => '添加詢價'),
        
        // ========== BREADCRUMBS ==========
        'home' => array('en' => 'Home', 'pt' => 'Início', 'zh' => '首页', 'zh-tw' => '首頁'),
        'breadcrumb' => array('en' => 'Breadcrumb', 'pt' => 'Navegação', 'zh' => '面包屑导航', 'zh-tw' => '麵包屑導航'),
        
        // ========== SEARCH & 404 ==========
        'search_results' => array('en' => 'Search Results', 'pt' => 'Resultados da Pesquisa', 'zh' => '搜索结果', 'zh-tw' => '搜尋結果'),
        'search' => array('en' => 'Search', 'pt' => 'Pesquisar', 'zh' => '搜索', 'zh-tw' => '搜尋'),
        'page_not_found' => array('en' => 'Page Not Found', 'pt' => 'Página Não Encontrada', 'zh' => '页面未找到', 'zh-tw' => '頁面未找到'),
        'page_not_found_message' => array('en' => 'Sorry, the page you are looking for could not be found.', 'pt' => 'Desculpe, a página que você está procurando não foi encontrada.', 'zh' => '抱歉，找不到您要查找的页面。', 'zh-tw' => '抱歉，找不到您要尋找的頁面。'),
        'go_back_home' => array('en' => 'Go Back Home', 'pt' => 'Voltar para Início', 'zh' => '返回首页', 'zh-tw' => '返回首頁'),
        
        // ========== NEWS SECTION ==========
        'discover_latest_news' => array('en' => 'Discover the Latest News', 'pt' => 'Descubra as Últimas Notícias', 'zh' => '发现最新资讯', 'zh-tw' => '發現最新資訊'),
        'read_more' => array('en' => 'Read More', 'pt' => 'Ler Mais', 'zh' => '阅读更多', 'zh-tw' => '閱讀更多'),
        'see_all_news' => array('en' => 'See All News', 'pt' => 'Ver Todas as Notícias', 'zh' => '查看全部资讯', 'zh-tw' => '查看全部資訊'),
        
        // News Categories
        'news_events' => array('en' => 'EVENTS', 'pt' => 'EVENTOS', 'zh' => '活动', 'zh-tw' => '活動'),
        'news_company' => array('en' => 'COMPANY NEWS', 'pt' => 'NOTÍCIAS DA EMPRESA', 'zh' => '公司新闻', 'zh-tw' => '公司新聞'),
        'news_sustainability' => array('en' => 'SUSTAINABILITY', 'pt' => 'SUSTENTABILIDADE', 'zh' => '可持续发展', 'zh-tw' => '可持續發展'),
        'news_service' => array('en' => 'NEW SERVICE', 'pt' => 'NOVO SERVIÇO', 'zh' => '新服务', 'zh-tw' => '新服務'),
        'news_partnership' => array('en' => 'PARTNERSHIP', 'pt' => 'PARCERIA', 'zh' => '合作伙伴', 'zh-tw' => '合作夥伴'),
        
        // News Titles
        'news_title_1' => array('en' => 'Angola B2B Participates in International Trade Fair 2025', 'pt' => 'Angola B2B Participa da Feira Internacional de Comércio 2025', 'zh' => 'Angola B2B参加2025年国际贸易博览会', 'zh-tw' => 'Angola B2B參加2025年國際貿易博覽會'),
        'news_excerpt_1' => array('en' => 'Angola B2B showcases its comprehensive range of construction and industrial equipment at the 2025 International Trade Fair in Luanda.', 'pt' => 'Angola B2B apresenta sua ampla gama de equipamentos de construção e industriais na Feira Internacional de Comércio 2025 em Luanda.', 'zh' => 'Angola B2B在卢安达2025年国际贸易博览会上展示其全面的建筑和工业设备系列。', 'zh-tw' => 'Angola B2B在盧安達2025年國際貿易博覽會上展示其全面的建築和工業設備系列。'),
        
        'news_title_2' => array('en' => 'New Agricultural Equipment Showcase at Farming Expo', 'pt' => 'Nova Exposição de Equipamentos Agrícolas na Feira Agrícola', 'zh' => '农业博览会新农业设备展示', 'zh-tw' => '農業博覽會新農業設備展示'),
        'news_excerpt_2' => array('en' => 'Visit our booth to discover the latest agricultural machinery and equipment designed to improve farm productivity.', 'pt' => 'Visite nosso estande para descobrir as mais recentes máquinas e equipamentos agrícolas projetados para melhorar a produtividade agrícola.', 'zh' => '参观我们的展位，发现旨在提高农场生产力的最新农业机械和设备。', 'zh-tw' => '參觀我們的展位，發現旨在提高農場生產力的最新農業機械和設備。'),
        
        'news_title_3' => array('en' => 'Angola B2B Expands Warehouse Facilities', 'pt' => 'Angola B2B Expande Instalações de Armazém', 'zh' => 'Angola B2B扩建仓储设施', 'zh-tw' => 'Angola B2B擴建倉儲設施'),
        'news_excerpt_3' => array('en' => 'New 10,000 sq ft warehouse in Luanda strengthens our commitment to serving customers with faster delivery times.', 'pt' => 'Novo armazém de 10.000 pés quadrados em Luanda reforça nosso compromisso de servir os clientes com tempos de entrega mais rápidos.', 'zh' => '在卢安达新建10,000平方英尺的仓库，加强我们以更快交货时间服务客户的承诺。', 'zh-tw' => '在盧安達新建10,000平方英尺的倉庫，加強我們以更快交貨時間服務客戶的承諾。'),
        
        'news_title_4' => array('en' => 'Green Equipment Initiative: Eco-Friendly Solutions', 'pt' => 'Iniciativa de Equipamentos Verdes: Soluções Ecológicas', 'zh' => '绿色设备倡议：环保解决方案', 'zh-tw' => '綠色設備倡議：環保解決方案'),
        'news_excerpt_4' => array('en' => 'Angola B2B introduces new line of environmentally friendly equipment with reduced emissions and improved efficiency.', 'pt' => 'Angola B2B apresenta nova linha de equipamentos ecologicamente corretos com emissões reduzidas e maior eficiência.', 'zh' => 'Angola B2B推出新系列环保设备，减少排放，提高效率。', 'zh-tw' => 'Angola B2B推出新系列環保設備，減少排放，提高效率。'),
        
        'news_title_5' => array('en' => 'Introducing 24/7 Technical Support Service', 'pt' => 'Apresentando Serviço de Suporte Técnico 24/7', 'zh' => '推出24/7技术支持服务', 'zh-tw' => '推出24/7技術支援服務'),
        'news_excerpt_5' => array('en' => 'Our new round-the-clock technical support ensures your operations never stop, with expert assistance available anytime.', 'pt' => 'Nosso novo suporte técnico 24 horas garante que suas operações nunca parem, com assistência especializada disponível a qualquer momento.', 'zh' => '我们全新的全天候技术支持确保您的运营永不停歇，专家协助随时可用。', 'zh-tw' => '我們全新的全天候技術支援確保您的運營永不停歇，專家協助隨時可用。'),
        
        'news_title_6' => array('en' => 'Strategic Partnership with Leading Manufacturer', 'pt' => 'Parceria Estratégica com Fabricante Líder', 'zh' => '与领先制造商建立战略合作伙伴关系', 'zh-tw' => '與領先製造商建立戰略合作夥伴關係'),
        'news_excerpt_6' => array('en' => 'Angola B2B forms exclusive partnership to bring world-class industrial equipment to the Angolan market.', 'pt' => 'Angola B2B forma parceria exclusiva para trazer equipamentos industriais de classe mundial ao mercado angolano.', 'zh' => 'Angola B2B建立独家合作伙伴关系，为安哥拉市场带来世界级工业设备。', 'zh-tw' => 'Angola B2B建立獨家合作夥伴關係，為安哥拉市場帶來世界級工業設備。'),
        
        // ========== SOCIAL SHARE ==========
        'share' => array('en' => 'Share', 'pt' => 'Compartilhar', 'zh' => '分享', 'zh-tw' => '分享'),
        
        // ========== CONTACT TOOLTIPS ==========
        'send_email_to' => array('en' => 'Send email to: %s', 'pt' => 'Enviar e-mail para: %s', 'zh' => '发送邮件至：%s', 'zh-tw' => '發送郵件至：%s'),
        'call_us' => array('en' => 'Call us: %s', 'pt' => 'Ligue para nós: %s', 'zh' => '致电：%s', 'zh-tw' => '致電：%s'),
    );
}

/**
 * 获取翻译文本
 * 
 * @param string $key 翻译键名
 * @param mixed $default 如果找不到翻译，返回的默认值
 * @return string 翻译后的文本
 */
function __t($key, $default = '') {
    // 优先使用自定义多语言系统
    $current_lang = 'en'; // 默认英语
    
    if (function_exists('angola_b2b_get_current_language')) {
        $lang_code = angola_b2b_get_current_language();
        
        // 自定义系统的语言代码映射
        if ($lang_code === 'pt') {
            $current_lang = 'pt';
        } elseif ($lang_code === 'zh_tw') {
            $current_lang = 'zh-tw';
        } elseif ($lang_code === 'zh') {
            $current_lang = 'zh';
        } elseif ($lang_code === 'en') {
            $current_lang = 'en';
        }
    } elseif (function_exists('pll_current_language')) {
        // 回退到Polylang（如果自定义系统不可用）
        $lang_code = pll_current_language();
        
        // 语言代码映射（更宽松的匹配）
        $lang_code_lower = strtolower($lang_code);
        
        if (strpos($lang_code_lower, 'pt') !== false) {
            // 葡萄牙语：pt, pt_PT, pt-PT, pt_pt
            $current_lang = 'pt';
        } elseif (strpos($lang_code_lower, 'zh-tw') !== false || strpos($lang_code_lower, 'zh_tw') !== false) {
            // 繁体中文：zh-tw, zh_TW, zh-TW
            $current_lang = 'zh-tw';
        } elseif (strpos($lang_code_lower, 'zh') !== false) {
            // 简体中文：zh, zh-cn, zh_CN
            $current_lang = 'zh';
        } elseif (strpos($lang_code_lower, 'en') !== false) {
            // 英语：en, en_US, en-US
            $current_lang = 'en';
        } else {
            // 未知语言，默认英语
            $current_lang = 'en';
        }
    }
    
    // 获取翻译数据
    $translations = angola_b2b_get_translations();
    
    // 返回对应语言的翻译
    if (isset($translations[$key]) && isset($translations[$key][$current_lang])) {
        return $translations[$key][$current_lang];
    }
    
    // 如果找不到，返回默认值
    return $default ? $default : $key;
}

/**
 * 输出翻译文本（已转义）
 */
function _et($key, $default = '') {
    echo esc_html(__t($key, $default));
}

/**
 * 输出翻译文本（带HTML，用于特殊情况）
 */
function _ht($key, $default = '') {
    echo __t($key, $default);
}

