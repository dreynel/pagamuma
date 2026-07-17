<?php
// c:\xampp\htdocs\pagamuma\seeder.php
require_once 'config/db.php';

$modules = [
    // --- HEALTH ---
    [
        'category' => 'health',
        'title_en' => 'Nutrition During First Trimester',
        'title_hil' => 'Pagsustansya sa Una nga mga Bulan',
        'title_tl' => 'Nutrisyon sa Unang Trimester',
        'content_en' => '<h3>A Journey through the First Trimester</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/GsMVhp3Qw58" title="Puwede at Bawal Kainin para sa Buntis" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/PoflO66C6iY" title="16 Prutas at Pagkain na Bawal sa Buntis" allowfullscreen></iframe>
        </div>
        <p>Eating well is one of the best things you can do during pregnancy. It forms the very foundation of your baby\'s health for the years to come. In these early days, you might find your appetite swinging wildly between ravenous hunger and sheer nausea, but ensuring your body gets the right fuel is critical.</p>
        <p>Focus on whole grains, fruits, vegetables, and lean proteins. These building blocks provide the essential macronutrients your body needs to undergo the miraculous transformation of growing a human. It is not just about eating more, but eating <em>smarter</em>.</p>
        <h4>The Importance of Folic Acid</h4>
        <p>Folic acid (Vitamin B9) is perhaps the most famous prenatal nutrient, and for good reason. It plays a massive role in preventing neural tube defects in the early stages of fetal development—often before a woman even realizes she is pregnant. You must take a supplement, but also seek it out in dark leafy greens like spinach and kale, as well as fortified cereals.</p>
        <h4>What to Avoid</h4>
        <p>Your immune system is naturally suppressed during pregnancy to protect the baby, meaning you are more susceptible to foodborne illnesses. Thus, you must absolutely avoid raw fish, unpasteurized cheeses, undercooked meats, and deli meats unless heated to steaming hot. These precautions keep listeria and other dangerous bacteria at bay.</p>
        <p>Remember, treating your body with grace and nourishing it gently is the first act of motherhood.</p>',
        'content_hil' => '<h3>Paglapit sa Una nga mga Bulan sang Pagbusong</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/GsMVhp3Qw58" title="Puwede at Bawal Kainin para sa Buntis" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/PoflO66C6iY" title="16 Prutas at Pagkain na Bawal sa Buntis" allowfullscreen></iframe>
        </div>
        <p>Ang pagkaon sing maayo amo ang isa sa pinakabuliganan nga himuon mo para sa imo lapsag. Ini ang pundasyon sang ikaayong lawas sang imo bata para sa mga sunod nga mga tuig. Sa sini nga aga nga panahon, mahimo nga magbag-o ang imo gana sa pagkaon, pero importante nga mahatagan ang imo lawas sang husto nga pagkaon.</p>
        <p>Mag-focus sa bigas kag tinapay, prutas, utanon, kag karne nga wala sing sobra nga taba. Sini nga mga pagkaon naga-hatag sang mga sustansya nga kinahanglan sang imo lawas para sa pagdaku sang bata sa sulod sang imo tiyan.</p>
        <h4>Ang Importansya sang Folic Acid</h4>
        <p>Ang folic acid (Vitamin B9) amo ang pinakasikat nga sustansya para sa pagbusong, kag may maayo nga rason para sini. Nagapahugas ini sang peligro sang depekto sa unod sang utak sang bata, labi na sa una nga mga semana sang pagbusong—bangod kaisa wala pa gani nakahibalo ang iloy nga nagabusong na siya. Kuhaa ang supplement, pero pangitaa man ini sa mga utanon nga maitom ang dahon pareho sang spinach kag kale.</p>
        <h4>Ano ang Dapat Likawan</h4>
        <p>Ang imo immune system nagapaubos sang iya resistensya sa panahon sang pagbusong para sa proteksyon sang bata, nagahimo ini nga mas mapihak ka sa mga sakit nga gikan sa pagkaon. Kag tungod sini, dapat absoluto kang malikaw sa hilaw nga isda, keso nga wala pa naproseso, kag karne nga daw hilaw pa. Ini nga mga pag-iingat nagaproteksyon sa imo kag sa imo bata.</p>
        <p>Dumdumon, ang pag-atipan sang imo lawas sing may paghigugma kag bukas nga pagkaon amo ang una nga buhat sang pagka-iloy.</p>',
        'content_tl' => '<h3>Isang Paglalakbay sa Unang Trimester</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/GsMVhp3Qw58" title="Puwede at Bawal Kainin para sa Buntis" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/PoflO66C6iY" title="16 Prutas at Pagkain na Bawal sa Buntis" allowfullscreen></iframe>
        </div>
        <p>Ang pagkain ng tama ay isa sa pinakamahusay na bagay na magagawa mo sa panahon ng pagbubuntis. Ito ang pundasyon ng kalusugan ng iyong sanggol para sa mga darating na taon. Sa mga unang araw na ito, maaaring malito ang iyong gana sa pagkain dahil sa pagduduwal, ngunit napakahalagang bigyan ang iyong katawan ng tamang sustansya.</p>
        <p>Mag-focus sa buong butil, prutas, gulay, at lean na protina. Ang mga pagkaing ito ang nagbibigay ng mahahalagang sustansya na kailangan ng iyong katawan para sa kahanga-hangang proseso ng pagpapalaki ng sanggol.</p>
        <h4>Ang Kahalagahan ng Folic Acid</h4>
        <p>Ang folic acid (Vitamin B9) ay marahil ang pinakakilalang sustansya sa pagbubuntis, at may magandang dahilan para dito. Malaki ang papel nito sa pag-iwas sa mga depekto sa neural tube ng sanggol sa maagang yugto ng pagbubuo nito—madalas bago pa man malaman ng babae na siya ay buntis. Kumuha ng supplement, ngunit hanapin din ito sa mga madilim na dahon ng gulay tulad ng spinach at kale.</p>
        <h4>Ano ang Dapat Iwasan</h4>
        <p>Ang iyong immune system ay natural na nagpapahina sa panahon ng pagbubuntis upang protektahan ang sanggol, kaya ikaw ay mas madaling mahawahan ng sakit mula sa pagkain. Kaya naman, dapat na ganap na iwasan ang hilaw na isda, hindi pasteurisadong keso, hilaw na karne, at mga deli meats maliban kung pinainit nang husto. Ang mga ito ay nagpoprotekta sa iyo at sa iyong sanggol mula sa mapanganib na bakterya.</p>
        <p>Tandaan, ang pag-aalaga sa iyong katawan nang may pagmamahal at tamang pagkain ang unang gawa ng pagiging ina.</p>'
    ],
    [
        'category' => 'health',
        'title_en' => 'Managing Morning Sickness',
        'title_hil' => 'Pagsabat sa Paglipong sa Aga',
        'title_tl' => 'Pag-iwas sa Morning Sickness',
        'content_en' => '<h3>Thriving Through Nausea</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/6iK9yUPYHAY" title="Sobrang Pagduduwal at Pagsusuka - Morning Sickness" allowfullscreen></iframe>
        </div>
        <p>Morning sickness is a massive misnomer; it can strike at dawn, dusk, or midnight. It is a hallmark of the first trimester, caused by the surge of pregnancy hormones like hCG. While it is a good sign of a robust pregnancy, it can make daily life feel like navigating a storm on a small boat.</p>
        <h4>Gentle Strategies</h4>
        <p>Eat small, frequent meals. An empty stomach triggers acid build-up, which exacerbates nausea. Keep a pack of plain saltine crackers right by your bedside, and nibble on one or two before your feet even touch the floor in the morning.</p>
        <p>Ginger is your best friend. From ginger tea to ginger ale (ensure it contains real ginger), to ginger candies—this root has been used for centuries to soothe turbulent stomachs.</p>
        <h4>Hydration is Key</h4>
        <p>Sometimes, drinking water can invoke nausea. If so, try adding slices of lemon, or sip on ice-cold water in tiny increments rather than large gulps. The goal is to remain hydrated, as dehydration worsens the dizzy, weak feeling associated with morning sickness.</p>
        <p>Be gentle with yourself. Rest when you need to, and know that for the vast majority of women, this phase lifts like a fog entering the second trimester.</p>',
        'content_hil' => '<h3>Pagbuntog sa Kahimtangan sang Paglisud</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/6iK9yUPYHAY" title="Sobrang Pagduduwal at Pagsusuka - Morning Sickness" allowfullscreen></iframe>
        </div>
        <p>Ang "morning sickness" isa ka sayop nga ngalan, bangod mahimo ini magpunta bisan san-o—subong sang aga, hapon, ukon gabi. Isa ini ka kilala nga tanda sang una nga tatlo ka bulan sang pagbusong, gikan sa pagdugmok sang mga hormone sang pagbusong pareho sang hCG. Bisan pa man nga maayo nga tanda ini, mahimo ini magpabagay sang imo adlaw-adlaw nga kinabuhi.</p>
        <h4>Mahamis nga mga Laygay</h4>
        <p>Magkaon sing amat-amat pero masunson. Ang bukas nga tiyan nagapagrabe sang kaasidad, nga nagladmon sang pagdulaw. Magbutang sing crackers sa lapit sang imo higdaan kag magkaon sing isa ka duha antes mag-bangon sa aga.</p>
        <p>Ang luya imo pinakamaagang abyan. Tikangkan man sa luya nga tsaa, luya nga baso, ukon mga kendi nga luya—ini nga gamot dugay na nga ginagamit para sa paghiwi sang tiyan.</p>
        <h4>Importante ang Pag-inom sang Tubig</h4>
        <p>Kaisa, ang pag-inom sang tubig mismo nagapagrabe sang pagdulaw. Kon amo man, subukana ang pagdugang sing hiwa nga limon, ukon mag- inom sing matugnaw sang tubig sa gamay-gamay nga tunga sangsa dako nga higop. Ang katuyuan amo ang magpabilin nga may sariwang lawas, bangod ang pagkauhaw nagapagrabe sang nahuhuyong nga pamatyag.</p>
        <p>Magmaabot sa imo kaugalingon. Magpahuway kon kinahanglan, kag dumdumon nga para sa kadamuon nga babayi, ini nga panahon nagahalin subong sang gabon sa pagsulod sang ikaduhang tatlo ka bulan.</p>',
        'content_tl' => '<h3>Pagtagumpay sa Kabila ng Pagduduwal</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/6iK9yUPYHAY" title="Sobrang Pagduduwal at Pagsusuka - Morning Sickness" allowfullscreen></iframe>
        </div>
        <p>Ang "morning sickness" ay isang maling pangalan, dahil maaari itong mangyari anumang oras—umaga, hapon, o gabi. Ito ay isang karaniwang tanda ng unang trimester, sanhi ng pagtaas ng mga hormone ng pagbubuntis tulad ng hCG. Kahit ito ay isang magandang tanda ng malusog na pagbubuntis, maaari itong gawing mahirap ang pang-araw-araw na buhay.</p>
        <h4>Malambot na mga Estratehiya</h4>
        <p>Kumain ng maliit ngunit madalas. Ang walang laman na tiyan ay nagdudulot ng pagtatayo ng asido, na nagpapalala ng pagduduwal. Magtabi ng mga plain crackers sa tabi ng iyong higaan, at kumain ng isa o dalawa bago pa man tumayo sa umaga.</p>
        <p>Ang luya ay ang iyong pinakamahusay na kaibigan. Mula sa luya na tsaa hanggang sa luya na kendi—ang ugat na ito ay ginagamit na sa loob ng maraming siglo para patahimikin ang gulong na tiyan.</p>
        <h4>Mahalaga ang Hydration</h4>
        <p>Minsan, ang pag-inom ng tubig mismo ay nagdudulot ng pagduduwal. Kung ganoon, subukan ang pagdaragdag ng hiwa ng limon, o uminom ng malamig na tubig nang paunti-unti sa halip na malalaking higop. Ang layunin ay manatiling maayos ang katawan, dahil ang kakulangan ng tubig ay nagpapalala ng makikilala na mahinang pakiramdam.</p>
        <p>Maging maawain sa iyong sarili. Magpahinga kapag kailangan, at alamin na para sa karamihan ng mga babae, ang yugtong ito ay naglalaho tulad ng ulap pagpasok ng ikalawang trimester.</p>'
    ],
    [
        'category' => 'health',
        'title_en' => 'Safe Exercises During Pregnancy',
        'title_hil' => 'Luwang nga Pag-esersisyo',
        'title_tl' => 'Ligtas na Pag-eehersisyo',
        'content_en' => '<h3>Moving with Your Baby</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/zmUJWKM98hM" title="Prenatal Yoga Workout" allowfullscreen></iframe>
        </div>
        <p>Gone are the days when pregnant women were told to stay confined to bed rest at the slightest discomfort. Today, we know that remaining active is profoundly beneficial for managing pregnancy weight, reducing back pain, and preparing your body for the physical marathon of labor.</p>
        <h4>Approved Activities</h4>
        <p>Walking is the simplest and safest cardiovascular exercise. It requires no equipment and can easily be paced to your comfort. Swimming is phenomenal; the bouyancy of the water supports your heavy ligaments, offering incredible relief for aching joints.</p>
        <p>Prenatal yoga focuses on flexibility, breathing, and mental centering. It also strengthens pelvic floor muscles, which are crucial for delivery. Be sure to attend classes specifically designed for pregnant women, as some deep twists or inversions are not recommended.</p>
        <h4>Listening to Your Body</h4>
        <p>Pregnancy alters your center of gravity and softens your ligaments, making you prone to sprains. Avoid high-impact sports, activities with a risk of falling (like horseback riding or skiing), and listen to your body. Ensure you can pass the "talk test" while exercising—if you are too breathless to hold a conversation, you are pushing too hard.</p>',
        'content_hil' => '<h3>Paglihok Upod ang Imo Lapsag</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/zmUJWKM98hM" title="Prenatal Yoga Workout" allowfullscreen></iframe>
        </div>
        <p>Wala na ang mga panahon nga ginasiling sa mga nagabusong nga babayi nga mag-atubang sa higdaan sa bisan anong kalisud. Subong, nahibal-an na naton nga ang pagpabilin sang aktibo makabulong para sa pagdumala sang timbang, pagpaminus sang sakit sa likod, kag paghanda sang lawas para sa pag-anak.</p>
        <h4>Mga Aprubado nga Gawi sang Pag-esersisyo</h4>
        <p>Ang paglakat-lakat amo ang pinakasimple kag pinakaligtas nga pag-esersisyo para sa puso. Wala ini nagakinahanglan sang mga kagamitan kag sayon nga ipahiangay sa imo maayo. Ang paglangoy talagang maayo; ang tubig nagasuporta sa imo mabug-at nga mga tuhod, nagahatag sang kaginhawahan sa mga nagasakit nga kasayoran.</p>
        <p>Ang prenatal yoga nag-focus sa kakayahan, pagginhawa, kag kabahin sang hunahuna. Nagapalig-on man ini sang mga singot sa pelvis, nga importante para sa pag-anak. Siguradoha nga mag-apil sa mga klase nga labi na gindesinyar para sa mga nagabusong nga babayi.</p>
        <h4>Pakinggan ang Imo Lawas</h4>
        <p>Ang pagbusong nagabag-o sang sentro sang imo balanse kag nagapahumok sang imo mga litid, nagahimo ini nga mas mapihak ka sa mga sprain. Likawi ang mga high-impact nga sports, mga gawi nga may peligro sang pagkahulog, kag pakinggan ang imo lawas. Siguradoha nga makaagi ka sa "talk test" samtang nag-eesersisyo—kon sobra ka na kahingal para magkuon, ipa-ubos na ang imo kusog.</p>',
        'content_tl' => '<h3>Galaw Kasama ang Iyong Sanggol</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/zmUJWKM98hM" title="Prenatal Yoga Workout" allowfullscreen></iframe>
        </div>
        <p>Wala na ang mga panahon na sinasabi sa mga buntis na manatiling nakahiga sa kahit anong kakulangan. Ngayon, nalalaman na natin na ang pagiging aktibo ay lubhang kapaki-pakinabang para sa pamamahala ng timbang, pagbabawas ng sakit sa likod, at paghahanda ng katawan para sa panganganak.</p>
        <h4>Mga Aprubadong Aktibidad</h4>
        <p>Ang paglalakad ang pinakasimple at pinakaligtas na cardiovascular exercise. Hindi ito nangangailangan ng kagamitan at madaling iayon sa iyong ginhawa. Ang paglalangoy ay kahanga-hanga; ang pagpapaliutad ng tubig ay sumusuporta sa iyong mabibigat na kasukasuan, nagbibigay ng kamangha-manghang ginhawa sa manigas na mga kasukasuan.</p>
        <p>Ang prenatal yoga ay nakatuon sa kakayahang umangkop, paghinga, at kaisipan. Pinapalakas din nito ang mga kalamnan sa pelvis, na mahalaga para sa panganganak. Siguraduhing dumalo sa mga klase na espesyal na dinisenyo para sa mga buntis.</p>
        <h4>Pakinggan ang Iyong Katawan</h4>
        <p>Ang pagbubuntis ay nagbabago ng iyong sentro ng gravitasyon at nagpapalambot ng iyong mga litid, na nagpapahina ng iyong balanse. Iwasan ang mga high-impact na sports, mga aktibidad na may panganib ng pagbagsak, at pakinggan ang iyong katawan. Siguraduhing makapasa sa "talk test" habang nag-eehersisyo—kung masyadong hikahos kang huminga para makipag-usap, pinalalakas mo nang labis.</p>'
    ],
    [
        'category' => 'health',
        'title_en' => 'Understanding Your Prenatal Vitamins',
        'title_hil' => 'Mga Bitamina para sa Pagbusong',
        'title_tl' => 'Pag-unawa sa Reseta ng Doktor',
        'content_en' => '<h3>Beyond the Basics</h3>
        <p>Prenatal vitamins are your nutritional insurance policy. Even with a perfect diet, the demands of growing a human can easily deplete your reserves of iron, calcium, and crucial B-vitamins.</p>
        <h4>Iron and Calcium</h4>
        <p>Your blood volume increases by up to 50% during pregnancy! Extra iron is non-negotiable to prevent anemia. However, iron can cause severe constipation, so ensure you drink plenty of water and consume high-fiber foods. Conversely, your baby is building an entire skeleton from scratch—if you do not consume enough calcium, your baby will draw it directly from your own bones.</p>',
        'content_hil' => '<h3>Labaw pa sa mga Ordinaryo</h3>
        <p>Ang mga prenatal vitamins amo ang imo seguro nga pang-sustansya. Bisan pa may perpektong diyeta ka, ang pagiging abala sang lawas sa pagpapalaki sang bata sadto magpahumod sang imo reserba sang iron, calcium, kag mga B-vitamins.</p>
        <h4>Iron kag Calcium</h4>
        <p>Ang imo dugo nagadugmok sing hangtod 50% sa panahon sang pagbusong! Ang sobra nga iron dili na mahimo isipon tungod sa pag-iwas sang anemia. Pero, ang iron mahimo magdulot sang grabeng tibi, kag tungod sini siguradoha nga mag-inom sang madamo nga tubig kag magkaon sang mga pagkaon nga may presko. Sa laing bahin, ang imo bata nagapatindog sang tibuok skeleto—kon kilat ka sa pagkaon sang calcium, ang imo bata magkuhaa gid ini direkta sa imo kaugalingon nga buto.</p>
        <p>Lagi-a ang pag-check sa imo doktor para mahibal-an ang sakto nga dosis sang imo mga bitamina. Ang imo doktor nahibal-an ang pinakahusto para sa imo kag sa imo lapsag.</p>',
        'content_tl' => '<h3>Higit pa sa mga Pangunahing Bagay</h3>
        <p>Ang mga prenatal vitamins ay ang iyong segurong pang-sustansya. Kahit may perpektong diyeta, ang pangangailangan ng pagpapalaki ng sanggol ay madaling maubos ang iyong reserba ng iron, calcium, at mahahalagang B-vitamins.</p>
        <h4>Iron at Calcium</h4>
        <p>Ang dami ng iyong dugo ay maaaring tumaas ng hanggang 50% sa panahon ng pagbubuntis! Ang dagdag na iron ay hindi maaaring ipagpaliban para maiwasan ang anemia. Gayunpaman, ang iron ay maaaring magdulot ng matinding constipation, kaya siguraduhing uminom ng maraming tubig at kumain ng mga pagkaing mayaman sa fiber. Sa kabilang banda, bumubuo ang iyong sanggol ng buong kalansay mula sa simula—kung hindi ka kumonsumo ng sapat na calcium, ang iyong sanggol ay kukuha nito nang direkta mula sa iyong mga buto.</p>
        <p>Lagi-laging kumonsulta sa iyong doktor para malaman ang tamang dosis ng iyong mga bitamina. Ang iyong doktor ang pinakamahusay na gabay para sa iyong kalusugan at sa iyong sanggol.</p>'
    ],

    // --- PARENTING ---
    [
        'category' => 'parenting',
        'title_en' => 'Preparing for Baby Arrivals',
        'title_hil' => 'Pagpreparar Para sa Lapsag',
        'title_tl' => 'Paghahanda sa Pagdating ng Sanggol',
        'content_en' => '<h3>The Final Countdown</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/TylF0ueGCTM" title="Hospital Bag Checklist" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/SeKIZy-1tDA" title="5 Pregnancy Tips You Should Not Ignore" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/aOxu0JTiwDY" title="Senyales na Malapit ng Manganak" allowfullscreen></iframe>
        </div>
        <p>The nesting instinct is a powerful phenomenon. As your due date approaches, you may feel an undeniable urge to clean the baseboards with a toothbrush and reorganize your pantry. Channel this energy into preparing your home for the little one\'s arrival.</p>
        <h4>Setting Up the Sanctuary</h4>
        <p>Set up the crib or bassinet at least a month early to ensure you have all the parts and the mattress fits snugly. Wash all newborn clothing and blankets in gentle, fragrance-free detergent explicitly designed for infant skin.</p>
        <h4>The Diaper Station</h4>
        <p>You will change thousands of diapers in your baby\'s first year. Over-preparedness is key here. Stock up your changing station with newborn diapers, a mountain of wipes, diaper rash cream, and extra layers of fresh clothing. Keep a change of clothes accessible for *yourself* as well—blowouts spare no one.</p>
        <p>Take it slow, write a checklist, and prioritize. The baby doesn\'t care if the nursery is perfectly decorated, only that it is safe, warm, and filled with love.</p>',
        'content_hil' => '<h3>Ang Katapusan nga Pagbilang</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/TylF0ueGCTM" title="Hospital Bag Checklist" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/SeKIZy-1tDA" title="5 Pregnancy Tips You Should Not Ignore" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/aOxu0JTiwDY" title="Senyales na Malapit ng Manganak" allowfullscreen></iframe>
        </div>
        <p>Ang instinto nga paghanda isa ka gamhanan nga dinaramdam. Samtang nagakahibag-o na ang imo petsa sang pag-anak, mahimo nga mabatyagan mo ang grabeng gusto nga limpyahan ang balay kag ayuson ang tanan. Gamiton ini nga inisyo para sa pagpreparar sang imo panimalay para sa pag-abot sang lapsag.</p>
        <h4>Pag-aman sang Lugar sang Lapsag</h4>
        <p>I-set up ang katre ukon bassinet sang lapsag sing labing gamay isa ka bulan antes sang imo petsa sing pag-anak para masigurado nga kumpleto ang tanan nga parte. Labaha ang tanan nga bayo kag habol sang lapsag gamit ang mahamis nga laba nga wala sing mabanglo para sa balat sang bata.</p>
        <h4>Ang Lugar sang Pagbag-o sang Diaper</h4>
        <p>Magbag-o ka sang libu-libo nga diaper sa una nga tuig sang imo bata. Ang pagiging handa sing labaw amo ang susi diri. Tipuna ang imo lugar sang pagbag-o sang diaper sang mga bagong diaper, madamo nga mga pampunas, cream para sa singot, kag sobra nga mga bayo. Magtipun man sing spare nga bayo para sa imo kaugalingon—bangod ang mga galisang sa diaper wala nagpili sang biktima.</p>
        <p>Maginay-inay. Magsulat sing listahan kag i-prioritize. Ang lapsag wala nagahambalan kon perpekto ang dekorasyon sang iya kwarto, ang importante lang amo nga luwang, mainit, kag puno sang gugma.</p>',
        'content_tl' => '<h3>Ang Huling Bilang</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/TylF0ueGCTM" title="Hospital Bag Checklist" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/SeKIZy-1tDA" title="5 Pregnancy Tips You Should Not Ignore" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/aOxu0JTiwDY" title="Senyales na Malapit ng Manganak" allowfullscreen></iframe>
        </div>
        <p>Ang instinto ng paghahanda ay isang makapangyarihang damdamin. Habang papalapit ang iyong takdang petsa ng panganganak, maaari kang makaramdam ng hindi mapigilan na pagnanais na linisin ang bahay at ayusin ang lahat. Gamitin ang enerhiyang ito sa paghahanda ng iyong tahanan para sa pagdating ng iyong munting prinsesa o prinsipe.</p>
        <h4>Pagtatatag ng Santuwaryo ng Sanggol</h4>
        <p>I-set up ang kuna o bassinet ng sanggol nang hindi bababa sa isang buwan bago ang iyong takdang petsa upang masiguro na kumpleto ang lahat ng parte. Labhan ang lahat ng damit at kumot ng sanggol gamit ang malambot na sabon na walang mabangong amoy na espesyal na dinisenyo para sa balat ng sanggol.</p>
        <h4>Ang Changing Station</h4>
        <p>Magpapalit ka ng libu-libong diaper sa unang taon ng iyong sanggol. Ang pagiging handa nang labis ay susi dito. I-stock ang iyong changing station ng mga diaper para sa bagong silang, maraming mga tisu, cream para sa pantal, at dagdag na mga damit. Magtabi rin ng pangpalit na damit para sa iyong sarili—walang takas sa mga blowout.</p>
        <p>Mag-dahan-dahan. Magsulat ng checklist at unahin ang mga importanteng bagay. Hindi pinapahalagahan ng sanggol kung perpekto ang dekorasyon ng silid niya, ang mahalaga lang ay ligtas, mainit, at puno ng pagmamahal.</p>'
    ],
    [
        'category' => 'parenting',
        'title_en' => 'Breastfeeding Basics 101',
        'title_hil' => 'Mga Sadsaran sa Pagpapasuso',
        'title_tl' => 'Pangunahing Kaalaman sa Pagpapasuso',
        'content_en' => '<h3>The Golden Milk</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/g_k50wOf564" title="Breastfeeding Basics" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/Qn7ouVsH0No" title="Pwede ba Magbreastfeed Kahit May Sakit si Mommy?" allowfullscreen></iframe>
        </div>
        <p>Breastfeeding is entirely natural, but it does not mean it is always naturally easy. It is a learned skill for both you and your newborn, requiring immense patience and practice.</p>
        <h4>The First Latch</h4>
        <p>In the moments following birth, placing the baby skin-to-skin on your chest encourages their natural instincts to crawl toward the breast and attempt their first latch. Let the nurses or a lactation consultant guide you. A proper latch is deep, asymmetrical, and while it might tug, it should never cause sharp, unbearable pain.</p>
        <h4>Colostrum: Liquid Gold</h4>
        <p>Before your milk fully "comes in", you will produce colostrum—a thick, yellowish substance packed with antibodies and perfect nutrition for your newborn\'s tiny stomach. Every single drop is incredibly valuable. Trust your body, stay hydrated, and feed on demand, as frequent feeding tells your body to produce more milk.</p>',
        'content_hil' => '<h3>Ang Bulawan nga Gatas</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/g_k50wOf564" title="Breastfeeding Basics" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/Qn7ouVsH0No" title="Pwede ba Magbreastfeed Kahit May Sakit si Mommy?" allowfullscreen></iframe>
        </div>
        <p>Ang pagpapasuso natural gid, pero indi ini palaging nagakahulugan nga natural man nga sayon. Isa ini ka natutunan nga kasanayan para sa imo kag sa imo bagong lapsag, nagakinahanglan sang dako nga pasyensya kag pagpraktis.</p>
        <h4>Ang Una nga Pagsupsop</h4>
        <p>Sa mga sandali pagkatapos sang pag-anak, ang pagbutang sang lapsag sa imo dibdib skin-to-skin nagapadasig sa iya natural nga instinto nga magpanaw padulong sa suso kag mag-umpisa sang una nga pagsupsop. Pabay-i ang mga nars ukon lactation consultant nga mag-giya sa imo. Ang husto nga pagsupsop madalum, katuhay, kag bisan nagahila, indi ini palaging nagakahulugan sang grabe nga sakit.</p>
        <h4>Colostrum: Likidong Bulawan</h4>
        <p>Antes pa magbukas ang bug-os nga gatas, magaprodyus ka sang colostrum—isa ka mainit, amarilyo nga substansya puno sang antibodies kag perpektong sustansya para sa gamay nga tiyan sang imo lapsag. Ang bisan ano ka gamay nga tulo sini talagang bilidhon gid. Tiwala sa imo lawas, mag-inom sing madamo, kag pasusohon sang nagakinahanglan, bangod ang masunson nga pagpapasuso nagapasugid sa imo lawas nga magprodyus sang sobra pa nga gatas.</p>',
        'content_tl' => '<h3>Ang Gintong Gatas</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/g_k50wOf564" title="Breastfeeding Basics" allowfullscreen></iframe>
        </div>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/Qn7ouVsH0No" title="Pwede ba Magbreastfeed Kahit May Sakit si Mommy?" allowfullscreen></iframe>
        </div>
        <p>Ang pagpapasuso ay ganap na natural, ngunit hindi ito nangangahulugang lagi itong natural na madali. Ito ay isang natutunanang kasanayan para sa iyo at sa iyong bagong silang, na nangangailangan ng napakaraming pasensya at pagsasanay.</p>
        <h4>Ang Unang Pagsipsip</h4>
        <p>Sa mga sandali pagkatapos ng panganganak, ang paglalagay ng sanggol sa iyong dibdib nang skin-to-skin ay nagpapalakas ng kanyang natural na instinto na pumunta sa suso at subukan ang kanyang unang pagsipsip. Hayaan ang mga nars o lactation consultant na gabayan ka. Ang tamang pagsipsip ay malalim, hindi pantay-pantay, at habang maaari itong humila, hindi ito dapat maging talamak at di-makatiis na sakit.</p>
        <h4>Colostrum: Likidong Ginto</h4>
        <p>Bago pa man lumabas ang buong gatas, maglalabas ka ng colostrum—isang makapal, dilaw na substance na puno ng antibodies at perpektong sustansya para sa maliit na tiyan ng iyong bagong silang. Bawat patak nito ay napakahalaga. Magtiwala sa iyong katawan, manatiling maayos ang hydration, at magpasuso kapag hinihingi ng sanggol, dahil ang madalas na pagpapasuso ay nagsasabi sa iyong katawan na gumawa ng mas maraming gatas.</p>'
    ],
    [
        'category' => 'parenting',
        'title_en' => 'Safe Sleep Guidelines (SIDS Prevention)',
        'title_hil' => 'Luwang nga Pagpatulog para sa Lapsag',
        'title_tl' => 'Ligtas na Pagpapatulog (Iwas SIDS)',
        'content_en' => '<h3>The ABCs of Safe Sleep</h3>
        <p>One of the most anxiety-inducing aspects of new parenting is sleep safety. Modern medical guidelines are incredibly strict but straightforward to drastically reduce the risk of Sudden Infant Death Syndrome (SIDS).</p>
        <h4>Alone, Back, Crib</h4>
        <p>Always place your baby on their <strong>Back</strong> to sleep. They should be in an approved <strong>Crib</strong> or bassinet. Crucially, they must sleep <strong>Alone</strong> — meaning no heavy blankets, no stuffed animals, no pillows, and no bumper pads. The sleep surface must be firm and strictly bare.</p>
        <p>Room-sharing (but not bed-sharing) is highly recommended for the first 6 months. It allows you to monitor them closely while keeping their dedicated sleep environment pristine and safe.</p>',
        'content_hil' => '<h3>Ang mga Sadsaran sang Luwang nga Pagtulog</h3>
        <p>Ang isa sa pinakagrabe nga pag-aalaga sa pagka-ginikanan bag-o amo ang kaligtasan sa pagtulog. Ang mga moderno nga medikal nga gabay talagang mahigpit pero klaro agud mapahumod ang peligro sang Sudden Infant Death Syndrome (SIDS).</p>
        <h4>Mag-isa, Nagatihaya, Katre</h4>
        <p>Pirme ibutang ang imo lapsag nga <strong>nagatihaya</strong> sa pagtulog sang iya. Kinahanglan siya sa isang aprubado nga <strong>katre</strong> ukon bassinet. Importante gid, kinahanglan tulugan niya sing <strong>mag-isa</strong>—nagakahulugan nga wala sing mabug-at nga habol, wala sing plush nga laruan, wala sing unlan, kag wala sing bumper pads. Ang lugar sang pagtulog kinahanglan matinog kag hubo gid.</p>
        <p>Ang pagsunod sa kwarto (pero indi sa isa ka higdaan) labi na inirekomenda para sa una nga 6 ka bulan. Nagapahanugot ini sa imo nga bantayan siya sing maayo samtang nagapanatili sang ligtas nga lugar sang pagtulog.</p>',
        'content_tl' => '<h3>Ang ABCs ng Ligtas na Pagtulog</h3>
        <p>Ang isa sa pinaka-nakakabalisang aspeto ng bagong pagiging magulang ay ang kaligtasan sa pagtulog. Ang mga modernong medikal na alituntunin ay napakahigpit ngunit malinaw para lubhang mabawasan ang panganib ng Sudden Infant Death Syndrome (SIDS).</p>
        <h4>Nag-iisa, Nakapatihaya, Kuna</h4>
        <p>Lagi-laging ilagay ang iyong sanggol na <strong>nakapatihaya</strong> kapag natutulog. Dapat siya ay nasa aprubadong <strong>kuna</strong> o bassinet. Higit sa lahat, dapat siyang matulog nang <strong>nag-iisa</strong>—nangangahulugang walang mabibigat na kumot, walang stuffed animals, walang unan, at walang bumper pads. Ang ibabaw ng higaan ay dapat matibay at walang laman.</p>
        <p>Ang pagiging magkasamang silid (ngunit hindi magkasama sa higaan) ay lubos na inirekomenda para sa unang 6 na buwan. Nagbibigay-daan ito sa iyo na bantayan siyang mabuti habang pinapanatiling malinis at ligtas ang kanyang lugar ng pagtulog.</p>'
    ],

    // --- EMOTIONAL ---
    [
        'category' => 'emotional',
        'title_en' => 'Dealing with Pregnancy Anxiety',
        'title_hil' => 'Pag-atubang sa Kabalaka sa Pagbusong',
        'title_tl' => 'Pagharap sa Pag-aalala sa Pagbubuntis',
        'content_en' => '<h3>The Weight of Expectation</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/yZAWU8rCEQs" title="Senyales na Hindi Safe ang Baby sa Tiyan" allowfullscreen></iframe>
        </div>
        <p>It is incredibly normal to feel a deep sense of anxiety during pregnancy. Culturally, society paints pregnancy as an era of continuous, glowing bliss, completely ignoring the sheer psychological shift of bringing new life into the world.</p>
        <h4>Naming the Fears</h4>
        <p>Whether you are anxious about medical tests, the pain of labor, or simply questioning whether you will be a "good" mother, naming these fears out loud removes their power over you. Speak openly with your partner, allowing them to carry the psychological load alongside you.</p>
        <h4>Seeking Professional Help</h4>
        <p>If your anxiety evolves into sleepless nights, intrusive thoughts, or begins impacting your appetite and daily functioning, it is paramount that you seek professional help. There is no shame in prenatal therapy or pursuing treatments guided by your health provider. Protecting your peace of mind is an essential form of prenatal care.</p>',
        'content_hil' => '<h3>Ang Kabug-at sang Pag-aalaga</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/yZAWU8rCEQs" title="Senyales na Hindi Safe ang Baby sa Tiyan" allowfullscreen></iframe>
        </div>
        <p>Normal lang nga mabalaka ka. I-istorya sa imo partner okon sa dokor ang imo ginabatyag.</p>',
        'content_tl' => '<h3>Ang Bigat ng Pag-aalala</h3>
        <div class="ratio ratio-16x9 mb-4 mt-3 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <iframe src="https://www.youtube.com/embed/yZAWU8rCEQs" title="Senyales na Hindi Safe ang Baby sa Tiyan" allowfullscreen></iframe>
        </div>
        <p>Normal lang ang makaramdam ng kaba. Kausapin ang iyong partner o doktor.</p>'
    ],
    [
        'category' => 'emotional',
        'title_en' => 'Navigating Body Image Changes',
        'title_hil' => 'Pagbaton sa Pagbag-o sang Imo Lawas',
        'title_tl' => 'Pagtanggap sa Pagbabago ng Katawan',
        'content_en' => '<h3>Embracing the Journey</h3>
        <p>Your body is currently undertaking one of the most mechanically and biologically complex tasks in nature. Yet, seeing the rapid expansion of your waistline, the appearance of stretch marks, or uncontrollable acne can take a severe toll on your self-esteem.</p>
        <h4>Reframing the Narrative</h4>
        <p>Try to shift your mindset from aesthetics to functionality. Your body isn\'t "getting fat"—it is actively generating a completely new organ (the placenta) and providing continuous life support. Treat your body with the exact same awe and respect you would give to the baby it carries.</p>',
        'content_hil' => '<p>Ang imo lawas naga-obra sang pinakamabaskog nga butang. Palangga-a ang imo lawas sa pihak sang pag daku.</p>',
        'content_tl' => '<p>Ang iyong katawan ay gumagawa ng isang himala. Mahalin ang sarili kahit ano pa ang pagbabago.</p>'
    ]
];

$stmt = $pdo->prepare("INSERT INTO educational_modules (category, title_en, title_hil, title_tl, content_en, content_hil, content_tl) VALUES (?, ?, ?, ?, ?, ?, ?)");

$pdo->query("TRUNCATE TABLE educational_modules");

foreach ($modules as $m) {
    $stmt->execute([
        $m['category'], $m['title_en'], $m['title_hil'], $m['title_tl'], 
        $m['content_en'], $m['content_hil'], $m['content_tl']
    ]);
}

echo "Seeding completed successfully with book-like chapters.\n";

// Seed Admin User
$adminEmail = 'admin@pagamuma.com';
$stmtAdmin = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmtAdmin->execute([$adminEmail]);
if (!$stmtAdmin->fetch()) {
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmtInsertAdmin = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmtInsertAdmin->execute(['RHU', 'Admin', $adminEmail, $adminPassword, 'admin']);
    echo "Admin user seeded (admin@pagamuma.com / admin123)\n";
} else {
    echo "Admin user already exists.\n";
}
?>
