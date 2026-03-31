/**
 * Утилиты для работы с placeholder изображениями товаров
 * Использует встроенные SVG вместо внешних сервисов
 */

const gradientPalette = [
    ['#6237F5', '#2563EB'],
    ['#CB11AB', '#8B5CF6'],
    ['#F59E0B', '#EF4444'],
    ['#10B981', '#3B82F6'],
    ['#8B5CF6', '#EC4899'],
    ['#0EA5E9', '#6366F1'],
    ['#14B8A6', '#0D9488'],
    ['#7C3AED', '#4F46E5'],
    ['#DB2777', '#9333EA'],
    ['#F97316', '#EF4444'],
];

/**
 * Определяет категорию товара по его названию
 */
export function detectCategory(name) {
    if (!name || typeof name !== 'string') return 'product';

    const n = name.toLowerCase();

    if (n.match(/одежда|футболка|рубашка|платье|юбка|брюки|джинсы|куртка|пальто|костюм|свитер|кофта|блузка|шорты|пиджак|майка|обувь|сапоги|ботинки|кроссовки|туфли|сумка|рюкзак|шарф|шапка|перчатки/)) return 'fashion';
    if (n.match(/телефон|смартфон|планшет|ноутбук|компьютер|наушники|телевизор|монитор|клавиатур|мышь|зарядка|адаптер|кабель|колонка|динамик|камера|фотоаппарат|электрон|гаджет|smart|iphone|samsung|xiaomi/)) return 'electronics';
    if (n.match(/косметик|крем|шампунь|маска|парфюм|духи|уход|макияж|помада|тушь|тени|румяна|пудра|лосьон|сыворотка|бальзам|гель|пена|скраб|масло|дезодорант/)) return 'beauty';
    if (n.match(/спорт|фитнес|йога|тренажер|велосипед|самокат|гантели|штанга|мяч|ракетка|лыжи|коньки|плавание|туризм|палатка/)) return 'sport';
    if (n.match(/продукт|еда|напиток|чай|кофе|сок|вода|молоко|сыр|масло|мясо|рыба|овощи|фрукты|хлеб|выпечка|шоколад|конфеты|печенье|торт/)) return 'food';
    if (n.match(/дом|для дома|кухня|посуда|тарелка|кружка|чашка|ложка|вилка|нож|сковород|кастрюля|чайник|утюг|пылесос|мебель|стол|стул|диван|кровать|шкаф|полка|декор|подушка|одеяло|полотенце|шторы|светильник|лампа/)) return 'home';

    return 'product';
}

function seedIndex(seed) {
    return String(seed).split('').reduce((acc, c) => acc + c.charCodeAt(0), 0);
}

/**
 * Генерирует SVG data URI для placeholder изображения
 */
export function getPlaceholderImage(width = 400, height = 400, category = 'product', seed = '') {
    const idx = seedIndex(seed || category);
    const [color1, color2] = gradientPalette[idx % gradientPalette.length];
    const letter = String(seed).charAt(0).toUpperCase() || 'Т';
    const fontSize = Math.round(Math.min(width, height) * 0.3);

    const svg = [
        `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">`,
        `<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">`,
        `<stop offset="0%" stop-color="${color1}"/>`,
        `<stop offset="100%" stop-color="${color2}"/>`,
        `</linearGradient></defs>`,
        `<rect width="${width}" height="${height}" fill="url(#g)"/>`,
        `<rect width="${width}" height="${height}" fill="rgba(255,255,255,0.06)"/>`,
        `<text x="50%" y="50%" font-family="system-ui,sans-serif" font-size="${fontSize}" font-weight="700" fill="rgba(255,255,255,0.9)" text-anchor="middle" dominant-baseline="middle">${letter}</text>`,
        `</svg>`,
    ].join('');

    return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}`;
}

/**
 * Получает URL изображения товара или SVG placeholder
 */
export function getProductImage(product, width = 400, height = 400) {
    if (product?.image_url) return product.image_url;
    if (product?.image_path) return `/storage/${product.image_path}`;

    const category = detectCategory(product?.name || '');
    const seed = product?.id || product?.name || 'product';

    return getPlaceholderImage(width, height, category, seed);
}

export default { getPlaceholderImage, getProductImage, detectCategory };
