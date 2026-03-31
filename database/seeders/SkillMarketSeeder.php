<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SkillMarketSeeder extends Seeder
{
    public function run(): void
    {
        $shopsData = [
            [
                'name' => 'Школа иностранных языков',
                'slug' => 'inostrannyye-yazyki',
                'description' => 'Онлайн-курсы иностранных языков: английский, подготовка к IELTS, курсы для переводчиков, изучение иероглифики и развитие речевого мышления.',
                'owner_email' => 'owner.languages@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Я — переводчик! VIP',
                        'slug' => 'perevodchik-vip',
                        'description' => 'Полная программа обучения переводческому мастерству. VIP-тариф включает все уровни курса, живые сессии с преподавателем, разбор сложных текстов и сертификат.',
                        'price' => 249042,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/1__1__hm3j-rb.png',
                    ],
                    [
                        'name' => 'Я — переводчик! Бестселлер',
                        'slug' => 'perevodchik-bestseller',
                        'description' => 'Бестселлер-пакет профессиональной подготовки переводчиков. Охватывает теорию и практику перевода, специализированную лексику и работу с профессиональными текстами.',
                        'price' => 147510,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/1__1_.png',
                    ],
                    [
                        'name' => 'Онлайн-курс «IELTS 7.0» Pro',
                        'slug' => 'ielts-7-pro',
                        'description' => 'Профессиональная подготовка к IELTS для получения балла 7.0 и выше. Включает стратегии выполнения всех разделов, имитацию экзамена и персональную обратную связь.',
                        'price' => 105326,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Ielts_pro_2_kbgz-91.png',
                    ],
                    [
                        'name' => 'Онлайн-курс «IELTS 7.0» Базовый',
                        'slug' => 'ielts-7-basic',
                        'description' => 'Базовая программа подготовки к экзамену IELTS. Систематизирует знания английского, знакомит со структурой экзамена и основными стратегиями сдачи.',
                        'price' => 61264,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Ielts_pro_2_kbgz-91_sh9m-ce.png',
                    ],
                    [
                        'name' => 'Английский язык',
                        'slug' => 'angliiskiy-yazyk',
                        'description' => 'Комплексный курс английского языка для всех уровней от A1 до C1. Грамматика, лексика, разговорная практика и тренировка всех языковых навыков.',
                        'price' => 40230,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/219df0c0e5e918e213acf45af5e8adc4.jpg',
                    ],
                    [
                        'name' => 'Я — переводчик! Стартовый',
                        'slug' => 'perevodchik-start',
                        'description' => 'Стартовый курс для начинающих переводчиков. Основы переводческой деятельности, базовые техники перевода и первые практические задания.',
                        'price' => 25287,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/1__1__dlcd-ud.png',
                    ],
                    [
                        'name' => 'Англ-Джангл: уроки английского языка',
                        'slug' => 'angl-dzhangle',
                        'description' => 'Интерактивные уроки английского в игровом формате «Английские джунгли». Идеально для детей и взрослых, которые хотят учиться без скуки.',
                        'price' => 24521,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Снимок_экрана_2025-01-24_171822.jpg',
                    ],
                    [
                        'name' => 'I CAN READ',
                        'slug' => 'i-can-read',
                        'description' => 'Курс по развитию навыка осознанного чтения на английском языке. Методики скорочтения, работа с текстом и расширение словарного запаса.',
                        'price' => 10345,
                        'stock' => 999,
                        'image_url' => null,
                    ],
                    [
                        'name' => 'Мини-курс «Иероглифика»',
                        'slug' => 'mini-kurs-ieroglifika',
                        'description' => 'Новый мини-курс по изучению японских и китайских иероглифов. Система запоминания, базовые знаки и основы письменной коммуникации на восточных языках.',
                        'price' => 7088,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Снимок_экрана_2025-01-24_173351.jpg',
                    ],
                    [
                        'name' => 'Практикум «Разгон речевого мышления»',
                        'slug' => 'razgon-rechevogo-myshleniya',
                        'description' => 'Интенсивный практикум по ускорению речевого мышления на иностранном языке. Снятие языкового барьера, беглость речи и уверенность в общении.',
                        'price' => 1877,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/istockphoto-482629377-612x612.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Академия бизнеса',
                'slug' => 'akademiya-biznesa',
                'description' => 'Профессиональные онлайн-курсы для бизнеса: управление, маркетинг, видеомонтаж, CRM-системы, онлайн-школы и карьерное развитие.',
                'owner_email' => 'owner.business@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Куратор онлайн-школы',
                        'slug' => 'kurator-onlayn-shkoly',
                        'description' => 'Полная программа подготовки кураторов онлайн-школ. Методология работы с учениками, сопровождение образовательного процесса, обратная связь и мотивация студентов.',
                        'price' => 268199,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Снимок_экрана_2025-01-24_163844.jpg',
                    ],
                    [
                        'name' => 'Онлайн-тренинг Александра Высоцкого',
                        'slug' => 'trening-vysotskogo',
                        'description' => 'Бизнес-тренинг по управлению компанией от Александра Высоцкого. Стратегическое планирование, построение команды, масштабирование бизнеса и личная эффективность руководителя.',
                        'price' => 141762,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/2__1_.png',
                    ],
                    [
                        'name' => 'Школа имиджа и стиля. Онлайн-курсы',
                        'slug' => 'shkola-imidzha-i-stilya',
                        'description' => 'Онлайн-курсы по имиджу и стилю для профессионального и личного развития. Цветотипы, гардероб, деловой стиль и формирование персонального бренда.',
                        'price' => 17772,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Школа_Шопинга.png',
                    ],
                    [
                        'name' => 'Образовательная услуга по видеомонтажу',
                        'slug' => 'videosmontazh-kurs',
                        'description' => 'Профессиональный курс по видеомонтажу: Adobe Premiere, Final Cut, работа со звуком, цветокоррекция и создание вирусного контента для соцсетей.',
                        'price' => 7663,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/373e6c198006213.Y3JvcCw4MjQsNjQ1LDAsMA.png',
                    ],
                    [
                        'name' => 'Многофункциональная онлайн CRM',
                        'slug' => 'onlayn-crm',
                        'description' => 'Подписка на многофункциональную онлайн CRM-систему для малого и среднего бизнеса. Ведение сделок, управление клиентами, автоматизация продаж.',
                        'price' => 7494,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/salebot.png',
                    ],
                    [
                        'name' => 'Мини-тренинг «Призвание и предназначение»',
                        'slug' => 'prizvanie-i-prednaznachenie',
                        'description' => 'Мини-тренинг по поиску призвания и раскрытию своего предназначения. Практические инструменты самопознания, карьерные ориентиры и план действий.',
                        'price' => 5556,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/360_F_63921992_6pLULgxV8gHwNyFNZ0ksDFsEvXQKc83Z.jpg',
                    ],
                    [
                        'name' => 'Проведение вебинара на платформе',
                        'slug' => 'provedenie-vebinara',
                        'description' => 'Сервис организации и проведения вебинаров на профессиональной платформе. Включает технический сетап, запись, рассылку участникам и аналитику.',
                        'price' => 3452,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/Bizon_365.jpg',
                    ],
                    [
                        'name' => 'Онлайн-курс 1С',
                        'slug' => 'onlayn-kurs-1s',
                        'description' => 'Онлайн-курс по работе в программе 1С: Бухгалтерия. Учёт операций, формирование отчётности, работа с документами и базовые настройки системы.',
                        'price' => 895,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/1с.png',
                    ],
                    [
                        'name' => 'Онлайн-библиотека: электронные и аудиокниги',
                        'slug' => 'onlayn-biblioteka',
                        'description' => 'Доступ к онлайн-библиотеке с тысячами электронных и аудиокниг по бизнесу, саморазвитию, психологии и профессиональным дисциплинам.',
                        'price' => 570,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/author.today.png',
                    ],
                ],
            ],
            [
                'name' => 'Центр здоровья и красоты',
                'slug' => 'zdorovye-i-krasota',
                'description' => 'Обучающие программы для специалистов в области косметологии, эстетической медицины и здоровья. Сертифицированные курсы для начинающих и опытных мастеров.',
                'owner_email' => 'owner.health@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Курс «Licensed Cosmetology»',
                        'slug' => 'licensed-cosmetology',
                        'description' => 'Профессиональный курс лицензированной косметологии. Теория и практика эстетических процедур, стандарты работы, лицензирование деятельности и работа с клиентами.',
                        'price' => 40077,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_l5m1-df.png',
                    ],
                    [
                        'name' => 'Программа обучения «Аппаратная косметология»',
                        'slug' => 'apparatnaya-kosmetologiya',
                        'description' => 'Комплексная программа обучения аппаратным методам косметологии. Работа с профессиональным оборудованием, протоколы процедур и техника безопасности.',
                        'price' => 35349,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_d8mv-ly.png',
                    ],
                    [
                        'name' => 'Аппаратная косметология (Базовый)',
                        'slug' => 'apparatnaya-kosmetologiya-basic',
                        'description' => 'Базовый курс аппаратных косметологических процедур. Принципы работы устройств, базовые протоколы и первые самостоятельные процедуры.',
                        'price' => 15249,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_8ijk-3j.png',
                    ],
                    [
                        'name' => 'Курс по лицензированию',
                        'slug' => 'kurs-po-litsenzirovaniyu',
                        'description' => 'Курс по получению лицензии для работы в сфере beauty. Юридические требования, оформление документов, стандарты санитарии и правовые основы деятельности.',
                        'price' => 23525,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_ig3a-gj.png',
                    ],
                    [
                        'name' => 'Курс о сне новорождённых',
                        'slug' => 'son-novorozhdyonnykh',
                        'description' => 'Образовательный курс для родителей и консультантов по сну новорождённых. Физиология детского сна, методики укладывания и режим дня грудного ребёнка.',
                        'price' => 7241,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/photo.png',
                    ],
                    [
                        'name' => '12-недельный курс для здоровой спины и суставов',
                        'slug' => '12-nedelniy-kurs-spina',
                        'description' => '12-недельная программа упражнений и рекомендаций для укрепления спины и суставов. Подходит для любого уровня физической подготовки, без специального оборудования.',
                        'price' => 3793,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/10.png',
                    ],
                    [
                        'name' => 'Программа обучения «LPG-массаж»',
                        'slug' => 'lpg-massazh',
                        'description' => 'Обучающая программа по технике LPG-массажа. Теория механического воздействия на ткани, протоколы работы с аппаратом и построение клиентского маршрута.',
                        'price' => 2234,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist.png',
                    ],
                    [
                        'name' => 'Программа обучения «Лечение акне»',
                        'slug' => 'lechenie-akne',
                        'description' => 'Специализированная программа по лечению акне и постакне. Диагностика типов акне, протоколы ухода, работа с препаратами и долгосрочный уход за проблемной кожей.',
                        'price' => 2352,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_06uh-kz.png',
                    ],
                    [
                        'name' => 'Программа обучения «Пилинги»',
                        'slug' => 'pilingi',
                        'description' => 'Курс по видам и технике проведения пилингов: поверхностные, срединные и глубокие. Показания, противопоказания, реабилитация и уход после процедур.',
                        'price' => 2352,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_m6k9-oy.png',
                    ],
                    [
                        'name' => 'Программа обучения «RF-лифтинг»',
                        'slug' => 'rf-lifting',
                        'description' => 'Обучение технике RF-лифтинга для омоложения и коррекции овала лица. Принципы радиочастотного воздействия, протоколы и результаты процедуры.',
                        'price' => 1169,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cosmetologist_n0im-sh.png',
                    ],
                ],
            ],
            [
                'name' => 'Психологический центр',
                'slug' => 'psikhologicheskiy-tsentr',
                'description' => 'Профессиональные программы для психологов, психотерапевтов и всех, кто интересуется психологией. Глубинная психология, психоанализ, работа с расстройствами.',
                'owner_email' => 'owner.psychology@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Psychoanalytic Psychotherapy: Relational Perspective',
                        'slug' => 'psychoanalytic-psychotherapy-relational',
                        'description' => 'Углублённый курс по психоаналитической психотерапии с реляционной перспективы. Для практикующих специалистов: теория привязанности, контрперенос и работа с переносом в долгосрочной терапии.',
                        'price' => 191188,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/3.png',
                    ],
                    [
                        'name' => 'Object Relations Theory in Treating Narcissistic Disorders',
                        'slug' => 'object-relations-narcissism',
                        'description' => 'Специализированный курс по теории объектных отношений в работе с нарциссическими расстройствами. Диагностика, терапевтический альянс и техники работы с нарциссическим клиентом.',
                        'price' => 187356,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/3_3xnq-j9.png',
                    ],
                    [
                        'name' => 'Психологический центр «Имеет смысл»',
                        'slug' => 'psikhologicheskiy-tsentr-imeet-smysl',
                        'description' => 'Комплексная программа для развития частной психологической практики. Методология работы, построение терапевтических отношений, самопомощь специалиста и бизнес-аспекты практики.',
                        'price' => 38314,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/makesense-logo--big-top.png',
                    ],
                    [
                        'name' => 'Психология здоровья',
                        'slug' => 'psikhologiya-zdorovya',
                        'description' => 'Вводный курс по психологии здоровья. Связь психики и тела, психосоматика, стресс и копинг-стратегии, здоровое мышление и профилактика психосоматических расстройств.',
                        'price' => 383,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/8637d190924ef949e9c271b07a0125c9.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Кулинарная студия',
                'slug' => 'kulinarnaya-studiya',
                'description' => 'Онлайн-курсы кулинарного мастерства: десерты, торты на заказ, свечеварение и профессиональные образовательные материалы для кондитеров.',
                'owner_email' => 'owner.culinary@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Курс «Десерты к праздникам» с куратором',
                        'slug' => 'deserty-k-prazdnikam-kurator',
                        'description' => 'Профессиональный курс по приготовлению праздничных десертов с личным куратором. Торты, муссы, макаруны и авторские рецепты с индивидуальным сопровождением и обратной связью.',
                        'price' => 82713,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cake_school_img_2.png',
                    ],
                    [
                        'name' => 'Курс «Десерты к праздникам» на заказ',
                        'slug' => 'deserty-k-prazdnikam-zakaz',
                        'description' => 'Курс по созданию праздничных десертов с упором на работу под заказ. Организация домашнего производства, ценообразование, работа с заказчиками и популярные форматы изделий.',
                        'price' => 55556,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/cake_school_img.png',
                    ],
                    [
                        'name' => 'Онлайн-курс «LUXURY CANDLES»',
                        'slug' => 'luxury-candles',
                        'description' => 'Полный онлайн-курс по созданию премиальных свечей ручной работы. Работа с воском, ароматизаторами, фитилями и упаковкой. Подходит для хобби и бизнеса.',
                        'price' => 20690,
                        'stock' => 999,
                        'image_url' => null,
                    ],
                    [
                        'name' => 'Образовательные материалы по кулинарии',
                        'slug' => 'kulinarnyye-obrazovatelnyye-materialy',
                        'description' => 'Сборник профессиональных образовательно-методических материалов по кулинарии. Рецептуры, технологические карты, стандарты безопасности и профессиональные техники.',
                        'price' => 2107,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/unnamed.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Цифровые навыки',
                'slug' => 'tsifrovyye-navyki',
                'description' => 'Онлайн-образование в сфере IT и цифровых профессий: программирование, аналитика данных, маркетинг, подготовка к экзаменам и обучение детей.',
                'owner_email' => 'owner.digital@skillmarket.by',
                'products' => [
                    [
                        'name' => 'Домашний лицей',
                        'slug' => 'domashniy-litsey',
                        'description' => 'Комплексная программа домашнего образования «Домашний лицей». Все предметы школьной программы в онлайн-формате с кураторами, тестами и системой прогресса.',
                        'price' => 34444,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skysmart_ijhw-sm_e9yg-ln_5brc-xc_7ava-ou.png',
                    ],
                    [
                        'name' => 'Курсы по аналитике данных',
                        'slug' => 'kursy-po-analitike',
                        'description' => 'Профессиональные курсы по аналитике данных: SQL, Python, визуализация данных, бизнес-анализ и работа с BI-инструментами. Для начинающих и продолжающих специалистов.',
                        'price' => 20433,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skypro_dwik-k1.png',
                    ],
                    [
                        'name' => 'Программирование для детей и подростков',
                        'slug' => 'programmirovanie-dlya-detey',
                        'description' => 'Онлайн-курс программирования для детей и подростков 8–17 лет. Scratch, Python, создание игр и приложений в игровом формате с опытными преподавателями.',
                        'price' => 19828,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skysmart_ijhw-sm.png',
                    ],
                    [
                        'name' => 'Курсы по маркетингу',
                        'slug' => 'kursy-po-marketingu',
                        'description' => 'Профессиональные курсы цифрового маркетинга: SMM, таргетированная реклама, контент-маркетинг, SEO и email-маркетинг. Практические задания и реальные кейсы.',
                        'price' => 18625,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skypro_dwik-k1_pq4m-2h.png',
                    ],
                    [
                        'name' => 'Подготовка к ЦТ на максимальный балл',
                        'slug' => 'podgotovka-k-tst',
                        'description' => 'Интенсивная программа подготовки к централизованному тестированию (ЦТ) на максимальный балл с любого начального уровня. Все предметы, стратегии и пробные тесты.',
                        'price' => 17625,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skysmart_ijhw-sm_e9yg-ln_5brc-xc.png',
                    ],
                    [
                        'name' => 'Онлайн-университет: Программирование',
                        'slug' => 'onlayn-universitet-programmirovanie',
                        'description' => 'Онлайн-университет любимой работы — факультет программирования. Full-stack разработка, выбор специализации, портфолио и помощь в трудоустройстве.',
                        'price' => 17985,
                        'stock' => 999,
                        'image_url' => 'https://skillmarket.by/images/detailed/8/skypro.png',
                    ],
                    [
                        'name' => 'Курс по автоэлектрике и диагностике',
                        'slug' => 'avtoelektrika-diagnostika',
                        'description' => 'Обучающий онлайн-курс по автомобильной электрике и диагностике. Принципы работы электросистем, использование диагностического оборудования и устранение типичных неисправностей.',
                        'price' => 6937,
                        'stock' => 999,
                        'image_url' => null,
                    ],
                ],
            ],
        ];

        Storage::disk('public')->makeDirectory('products');

        foreach ($shopsData as $shopData) {
            $owner = User::query()->updateOrCreate(
                ['email' => $shopData['owner_email']],
                [
                    'name' => $shopData['name'],
                    'role' => UserRole::OWNER,
                    'email_verified_at' => now(),
                    'password' => 'password',
                ]
            );

            $shop = Shop::query()->updateOrCreate(
                ['slug' => $shopData['slug']],
                [
                    'owner_id' => $owner->id,
                    'name' => $shopData['name'],
                    'description' => $shopData['description'],
                    'is_active' => true,
                ]
            );

            foreach ($shopData['products'] as $productData) {
                $imagePath = $this->downloadImage($productData['slug'], $productData['image_url'] ?? null);

                Product::query()->updateOrCreate(
                    ['shop_id' => $shop->id, 'slug' => $productData['slug']],
                    [
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'stock' => $productData['stock'],
                        'is_active' => true,
                        'image_path' => $imagePath,
                    ]
                );
            }
        }
    }

    private function downloadImage(string $slug, ?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $response = Http::timeout(15)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $path = "products/{$slug}.{$extension}";
            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Exception) {
            return null;
        }
    }
}
