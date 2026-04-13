// ============================================
// COMPLETE MODULE DATA - Based on researcher files
// ============================================

const modules = [
    // ============================================
    // MODULE 1: Tour Preparation (UNLOCKED)
    // ============================================
    {
        id: 1,
        title: 'Tour Preparation',
        icon: 'clipboard-list',
        color: 'success',
        intro: `Tour guiding is often seen as a job that starts when you meet your guests — but in reality, the tour begins long before the first handshake or welcome speech. What happens in the preparation stage determines the smoothness, quality, and safety of the entire experience.

Guests may remember your stories and energy, but the foundation of their satisfaction comes from things they never see — the calls you made, the notes you took, the alternate routes you mapped, and the coordination you arranged before the tour even began.

In this section, we will take a deep look at the pre-tour preparation process. You'll learn why it is the most important stage of the guiding role, how to execute it step-by-step, and how to avoid common mistakes that can cause unnecessary stress for both you and your guests.`,
        lessons: [
            {
                id: 1,
                title: 'General Preparation Before the Tour',
                content: `Even if you've guided the same tour many times, no two are ever the same. Weather, traffic, guest expectations, and site conditions can change overnight. Being prepared means you're ready for anything — from sudden route changes to unexpected guest needs.

<h6 class="fw-bold mt-4"><i class="fas fa-hiking me-2"></i>Know the tour style</h6>
<p>Walking tours require stamina and weather planning. Coach tours need precise timing to avoid delays. Museum or cultural tours demand accurate knowledge and respectful conduct.</p>

<h6 class="fw-bold mt-3"><i class="fas fa-map-marked-alt me-2"></i>Review the itinerary</h6>
<p>Things change. Check each stop, travel time, and sequence. Identify areas that may require flexibility.</p>

<h6 class="fw-bold mt-3"><i class="fas fa-briefcase me-2"></i>Understand employer's standards</h6>
<p>Dress codes, documentation, and specific service expectations vary by operator. Always represent your company professionally.</p>`,
                quickCheck: {
                    questions: [
                        { question: 'Why should you review an itinerary even for a tour you\'ve done before?', options: ['To memorize guest names', 'To check for changes in stops', 'To prepare trivia questions', 'To avoid coordinating with suppliers'], correct: 1 },
                        { question: 'True or False: Weather planning is important for both walking and coach tours.', options: ['True', 'False'], correct: 0 },
                        { question: 'Scenario: You\'re guiding a walking tour and the weather forecast shows rain. What should you do?', options: ['Cancel the tour immediately', 'Prepare umbrellas and adjust the route', 'Ignore the forecast', 'Shorten the tour without telling guests'], correct: 1 },
                        { question: 'Scenario: Your employer\'s policy requires a specific uniform and ID badge, but you forgot your badge at home. What\'s the best solution?', options: ['Borrow a badge from another guide', 'Wear plain clothes', 'Tell your employer and arrange a temporary ID', 'Ignore the policy'], correct: 2 },
                        { question: 'Which preparation step ensures you follow the company\'s professional standards?', options: ['Knowing dress code and documents', 'Reviewing weather patterns', 'Carrying extra water bottles', 'Researching local events'], correct: 0 }
                    ]
                }
            },
            {
                id: 2,
                title: 'Researching Your Incoming Group',
                content: `Every group is different — and a good tour guide knows how to adapt. Understanding who your guests are lets you create a more relevant, comfortable, and engaging experience.

<h6 class="fw-bold mt-4">Why Research Guests?</h6>
<ul><li>Customize commentary to match their interests</li><li>Avoid repeating information they already know</li><li>Ensure comfort, safety, and engagement</li></ul>

<h6 class="fw-bold mt-3">Information to Gather:</h6>
<ul><li>Special needs & interests</li><li>Group dynamics</li><li>Origin and cultural context</li><li>Past experiences</li><li>Fitness & health levels</li><li>Allergies & medical conditions</li></ul>

<h6 class="fw-bold mt-3">How to Gather Information:</h6>
<p><strong>1. Ask Your Operations Manager</strong> - They receive group profiles from travel agents.</p>
<p><strong>2. Email the Guests in Advance</strong> - Introduce yourself and ask about interests.</p>
<p><strong>3. Contact Previous Tour Guides</strong> - They are gold mines of information.</p>`,
                quickCheck: {
                    questions: [
                        { question: 'Why is guest profiling important?', options: ['To create a tailored experience', 'To ensure all tours follow the same format', 'To shorten preparation time', 'To impress suppliers'], correct: 0 },
                        { question: 'Scenario: You discover one guest uses a wheelchair but the itinerary includes steep stairs. What\'s the best action?', options: ['Pretend you didn\'t notice', 'Arrange an accessible alternative', 'Ask another guest to carry the wheelchair', 'Skip the site without explanation'], correct: 1 },
                        { question: 'Who can provide reliable group profiles?', options: ['Random tourists', 'The Operations Manager or travel agent', 'Restaurant waiters', 'Social media followers'], correct: 1 },
                        { question: 'Scenario: The previous guide says the group dislikes long bus rides. What should you do?', options: ['Change routes without telling anyone', 'Add commentary and activities', 'Ignore the feedback', 'Cancel the bus trips'], correct: 1 },
                        { question: 'Which of these is NOT part of profiling?', options: ['Swimming ability', 'Dietary needs', 'Favorite movies', 'Activity level'], correct: 2 }
                    ]
                }
            },
            {
                id: 3,
                title: 'Coordinating with Suppliers',
                content: `Suppliers — hotels, restaurants, transport providers — are your allies in delivering a smooth tour. Good coordination ensures guests enjoy a seamless experience.

<h6 class="fw-bold mt-4"><i class="fas fa-hotel me-2"></i>Hotels</h6>
<p>Confirm the number of rooms, bed arrangements, and check-in/out times.</p>

<h6 class="fw-bold mt-3"><i class="fas fa-bus me-2"></i>Transportation</h6>
<p>Verify pick-up times, routes, and vehicle condition.</p>

<h6 class="fw-bold mt-3"><i class="fas fa-utensils me-2"></i>Restaurants</h6>
<p>Double-check seating arrangements, meal types, and dietary accommodations.</p>

<h6 class="fw-bold mt-3"><i class="fas fa-ticket-alt me-2"></i>Attractions</h6>
<p>Ensure tickets are ready and opening hours are correct.</p>`,
                quickCheck: {
                    questions: [
                        { question: 'Which is NOT a typical supplier for a tour guide?', options: ['Hotels', 'Bus companies', 'Restaurants', 'Jewelry manufacturers'], correct: 3 },
                        { question: 'Scenario: A restaurant confirms your booking but forgets about a vegetarian guest. What\'s the best action?', options: ['Ignore it', 'Request an alternative meal', 'Ask the guest to eat meat', 'Apologize but do nothing'], correct: 1 },
                        { question: 'When coordinating with transportation, you should:', options: ['Confirm pick-up, routes, and vehicle condition', 'Only check the route details', 'Ask the driver about hobbies', 'Skip confirmation to save time'], correct: 0 },
                        { question: 'Scenario: You arrive at an attraction and find it closed for maintenance. What\'s the best response?', options: ['Tell guests to wait', 'Apologize and offer an alternative site', 'End the tour early', 'Blame the guests'], correct: 1 },
                        { question: 'Which supplier is responsible for ensuring seating arrangements match group size?', options: ['Restaurants', 'Bus companies', 'Hotels', 'Attractions'], correct: 0 }
                    ]
                }
            },
            {
                id: 4,
                title: 'Site Familiarization',
                content: `Confidence is built on knowledge of the terrain. Guests expect you to know the way — literally and figuratively.

Even familiar sites change over time. Seasonal closures, renovations, or new attractions can appear. A site visit helps you:

<ul class="mt-3">
<li><i class="fas fa-restroom me-2"></i>Locate restrooms, shops, and emergency facilities</li>
<li><i class="fas fa-shield-alt me-2"></i>Check the safety of paths, especially for elderly or disabled guests</li>
<li><i class="fas fa-camera me-2"></i>Identify scenic photo spots and optimal viewing times</li>
<li><i class="fas fa-umbrella-beach me-2"></i>Find shaded areas or shelters in case of bad weather</li>
</ul>`,
                quickCheck: {
                    questions: [
                        { question: 'Why do an ocular visit even for familiar sites?', options: ['To memorize all shop names', 'Because sites may have seasonal changes', 'To meet local vendors', 'To avoid carrying maps'], correct: 1 },
                        { question: 'Scenario: You find a path to a viewpoint is too steep for elderly guests. What should you do?', options: ['Avoid the site and offer an easier alternative', 'Take them up anyway', 'Ask them to stay behind', 'Carry them to the top'], correct: 0 },
                        { question: 'Which of the following is NOT part of site familiarization?', options: ['Checking safety of paths', 'Identifying scenic photo spots', 'Locating restrooms', 'Negotiating discounts with vendors'], correct: 3 },
                        { question: 'Scenario: An event may cause road closures during your tour. What\'s the best approach?', options: ['Ignore it', 'Plan an alternative route or adjust timing', 'Cancel the tour', 'Let guests figure it out'], correct: 1 },
                        { question: 'Why should you note shaded or sheltered areas?', options: ['For breaks in bad weather or heat', 'For decoration ideas', 'To avoid paying entrance fees', 'To store luggage'], correct: 0 }
                    ]
                }
            }
        ],
        brainWarmup: {
            questions: Array(25).fill(null).map((_, i) => ({
                question: `Brain Warm-up Question ${i+1} for Module 1`,
                options: ['Option A', 'Option B', 'Option C', 'Option D'],
                correct: 1
            }))
        },
        endOfModuleEval: {
            questions: Array(25).fill(null).map((_, i) => ({
                question: `End-of-Module Evaluation Question ${i+1} for Module 1`,
                options: ['Option A', 'Option B', 'Option C', 'Option D'],
                correct: 1
            }))
        }
    },
    
    // ============================================
    // MODULE 2: Tour Briefings (LOCKED)
    // ============================================
    {
        id: 2,
        title: 'Tour Briefings',
        icon: 'users',
        color: 'primary',
        intro: `Imagine this: You're about to lead a group of eager travelers through some of the most fascinating sights and stories of a destination. But before the adventure begins, you have just one chance to capture their attention, set the tone, and make them feel comfortable and excited for the journey ahead. This moment is called the tour briefing — and it's your opportunity to shine as a professional guide.

A great tour briefing isn't just about giving information — it's about creating connection, building trust, and energizing your group for the experiences to come. Think of it as the welcome mat to your entire tour. How you deliver this briefing can make the difference between a smooth, enjoyable tour and one filled with confusion or frustration.

In this section, we'll explore what makes a tour briefing effective, how to engage your group from the start, and practical tips to help you become a confident and memorable guide.`,
        lessons: [
            {
                id: 1,
                title: 'Welcome',
                content: `<h6 class="fw-bold">The Importance of a Warm and Professional Welcome</h6>
<p>Your welcome is arguably one of the most critical moments of the entire experience. It serves as the first direct interaction between you and the travelers.</p>

<h6 class="fw-bold mt-4">Why is the welcome so important?</h6>
<ul><li><strong>First Impressions Last</strong> - People remember how they were greeted</li>
<li><strong>Builds Trust and Rapport</strong> - Breaks the ice and eases anxieties</li>
<li><strong>Represents the Tour Company</strong> - You are the face of the company</li></ul>

<h6 class="fw-bold mt-4">Steps to Deliver an Effective Welcome:</h6>
<ol><li>Choose an Appropriate Location</li><li>Gather the Group Promptly</li><li>Express Gratitude</li><li>State the Tour Name Clearly</li></ol>

<p class="fst-italic mt-3">"Good morning, everyone! Thank you so much for choosing the 'City Highlights Explorer' tour. My name is Alex, and I'm thrilled to be your guide!"</p>`,
                quickCheck: {
                    questions: [
                        { question: 'The first step of a comprehensive tour briefing is:', options: ['Travel tips', 'Welcome', 'Tour requirements', 'Documents'], correct: 1 },
                        { question: 'A strong welcome represents the professionalism of the guide and company.', options: ['True', 'False'], correct: 0 },
                        { question: 'What phrase highlights the impact of the first moments of a tour?', options: ['A strong start lasts', 'First impressions last', 'Confidence builds trust', 'Memories matter most'], correct: 1 },
                        { question: 'When introducing yourself, it\'s helpful to:', options: ['Tell your life story', 'Clearly state your role', 'Avoid giving personal contact', 'Skip your responsibilities'], correct: 1 },
                        { question: 'Thanking guests for joining is optional.', options: ['True', 'False'], correct: 1 }
                    ]
                }
            },
            {
                id: 2,
                title: 'Getting Acquainted',
                content: `<h6 class="fw-bold">Introducing Yourself</h6>
<p>Be concise but informative. Clearly state your name, role, and responsibilities.</p>
<p class="fst-italic">"Hello everyone, my name is Alex, and I will be your tour guide. My job is to ensure you have a safe, enjoyable, and memorable experience."</p>

<h6 class="fw-bold mt-4">Introducing Other Team Members</h6>
<p>Introduce your assistant guide, driver, interpreter, or site expert. Explain their roles and expertise.</p>
<p class="fst-italic">"This is Mr. Somchai, our driver. He has over 15 years of experience navigating these roads."</p>

<h6 class="fw-bold mt-4">Introducing Tour Members to Each Other</h6>
<ul><li>Provide name badges</li><li>Organize brief icebreaker activities</li><li>Ask fun questions to spark conversation</li></ul>`,
                quickCheck: {
                    questions: [
                        { question: 'To help guests remember each other\'s names, the guide can provide:', options: ['Vouchers', 'Name badges', 'Maps', 'Brochures'], correct: 1 },
                        { question: 'What important item can you provide guests to reassure them help is always available?', options: ['Your email', 'Your phone number', 'Your passport copy', 'Your ID'], correct: 1 },
                        { question: 'Facilitating introductions among guests encourages group bonding.', options: ['True', 'False'], correct: 0 },
                        { question: 'One team member doesn\'t speak English. What should you do?', options: ['Skip introducing them', 'Introduce them and explain their role', 'Ask them not to interact', 'Pretend they are not part of the team'], correct: 1 },
                        { question: 'What is the main benefit of icebreaker activities?', options: ['Build group connection', 'Delay the tour start', 'Confuse the guests', 'Fill extra time'], correct: 0 }
                    ]
                }
            },
            {
                id: 3,
                title: 'Tour Essentials',
                content: `<h6 class="fw-bold">Delivering Tour Information Effectively</h6>
<ul><li><strong>Confirm the Itinerary</strong> - Provide printed copies, walk through activities</li>
<li><strong>Confirm Timing Details</strong> - Travel times, duration at stops, return times</li>
<li><strong>Prepare for Changes</strong> - Local conditions may require adjustments</li>
<li><strong>Clarify Inclusions and Exclusions</strong> - Meals, drinks, tickets, transport</li>
<li><strong>Manage Expectations</strong> - Service standards may differ</li>
<li><strong>Distribute Tour Materials</strong> - Name badges, vouchers, safety equipment</li></ul>`,
                quickCheck: {
                    questions: [
                        { question: 'Which part of the briefing involves explaining inclusions and exclusions?', options: ['Travel tips', 'Documents', 'Tour information', 'Questions'], correct: 2 },
                        { question: 'Clearly stating inclusions and exclusions helps avoid surprises.', options: ['True', 'False'], correct: 0 },
                        { question: 'Guests usually respond better to clearly explained itinerary changes.', options: ['True', 'False'], correct: 0 },
                        { question: 'A guest asks if dinner is included daily. What do you do?', options: ['Say yes to keep them happy', 'Ignore the question', 'Clarify inclusions and exclusions', 'Ask them to check their voucher'], correct: 2 },
                        { question: 'When distributing brochures or vouchers, what else should you do?', options: ['Explain their purpose', 'Hand them silently', 'Keep them until the end', 'Give them only to team members'], correct: 0 }
                    ]
                }
            },
            {
                id: 4,
                title: 'Group Conduct Guidelines',
                content: `<h6 class="fw-bold">1. Safety on the Tour Vehicle</h6>
<p>Wear seat belts, proper rubbish disposal, share front seat fairly, eating/drinking rules.</p>

<h6 class="fw-bold mt-4">2. Respecting Local Culture and Customs</h6>
<p>Always ask permission before taking photos of people or sacred places.</p>

<h6 class="fw-bold mt-4">3. Group Harmony and Personal Responsibility</h6>
<p>If separated, go to the agreed meeting point, call the guide, or remain where you are.</p>`,
                quickCheck: {
                    questions: [
                        { question: 'Wearing seatbelts in the tour vehicle is optional.', options: ['True', 'False'], correct: 1 },
                        { question: 'Respecting local customs enriches the overall journey.', options: ['True', 'False'], correct: 0 },
                        { question: 'A guest gets separated from the group. What should they do first?', options: ['Find transport back', 'Stay where they are or go to meeting point', 'Call another tourist', 'Panic'], correct: 1 },
                        { question: 'A guest wants to take photos of a local person. What should you remind them?', options: ['It\'s always allowed', 'Ask permission first', 'It\'s prohibited', 'Only guides can take photos'], correct: 1 },
                        { question: 'The front seat of the tour vehicle should be:', options: ['Reserved for guide', 'Shared fairly among guests', 'First-come first-served', 'Left empty'], correct: 1 }
                    ]
                }
            }
        ],
        brainWarmup: { questions: Array(30).fill(null).map((_, i) => ({ question: `Brain Warm-up Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) },
        endOfModuleEval: { questions: Array(30).fill(null).map((_, i) => ({ question: `Evaluation Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) }
    },
    
    // ============================================
    // MODULE 3: Tour Information Delivery (LOCKED)
    // ============================================
    {
        id: 3,
        title: 'Tour Information Delivery',
        icon: 'comments',
        color: 'warning',
        intro: `Tour information delivery is a very important part of any guided tour. It is the stage when the tour guide shares detailed facts, stories, and explanations about the place being visited. This step turns a simple visit into an interesting and educational experience. How well the guide delivers this information greatly affects how much visitors learn and enjoy their time at the destination.

This stage is not just about reading facts or repeating a script. Instead, it is about creating a story that grabs the visitors' attention, educates them, and makes them curious to know more. It helps visitors connect emotionally and intellectually with the destination by providing the background and significance of the place.`,
        lessons: [
            {
                id: 1,
                title: 'Strategies for Effective Tour Information Delivery',
                content: `<h6 class="fw-bold">Content of Information</h6>
<ul><li>Historical facts - key events, dates, people</li><li>Cultural significance - local traditions</li><li>Unique features - natural or architectural</li><li>Stories and legends - bring facts to life</li><li>Practical details - facilities, safety</li></ul>

<h6 class="fw-bold mt-4">Methods of Delivery</h6>
<ul><li>Storytelling - connects facts with emotions</li><li>Asking questions - invites participation</li><li>Visual aids - maps, photos, objects</li><li>Interactive elements - touch, smell</li><li>Clear and simple language</li><li>Tone, body language, enthusiasm</li></ul>`,
                quickCheck: {
                    questions: [
                        { question: 'What does tour information delivery mainly involve?', options: ['Sharing stories and facts', 'Avoiding visitor interaction', 'Reading from a manual', 'Giving directions only'], correct: 0 },
                        { question: 'Which factor makes a visit educational and meaningful?', options: ['Ticket prices', 'Tour information delivery', 'Bus schedules', 'Weather conditions'], correct: 1 },
                        { question: 'What do stories and legends help with?', options: ['Aiding memory', 'Shortening tours', 'Replacing safety rules', 'Avoiding facts'], correct: 0 },
                        { question: 'Which tool can illustrate abstract ideas better?', options: ['Visual aids', 'Jokes', 'Tickets', 'Receipts'], correct: 0 },
                        { question: 'What is the role of asking questions during a tour?', options: ['To keep attention', 'To save time', 'To confuse visitors', 'To avoid talking'], correct: 0 }
                    ]
                }
            },
            {
                id: 2,
                title: 'Introduction to Crisis Management During the Tour',
                content: `<h6 class="fw-bold">Understanding Potential Crises</h6>
<ul><li>Medical emergencies - illness, injuries, allergic reactions</li><li>Natural disasters - earthquakes, floods, storms</li><li>Security issues - theft, violence</li><li>Transport problems - breakdowns, delays</li><li>Environmental hazards - wildlife, extreme weather</li></ul>

<h6 class="fw-bold mt-4">Preparation and Planning</h6>
<ul><li>Risk assessments</li><li>Emergency plans</li><li>First aid training</li><li>Communication tools</li><li>Brief visitors on safety</li></ul>

<h6 class="fw-bold mt-4">Immediate Response</h6>
<ul><li>Stay calm</li><li>Assess the situation</li><li>Provide immediate help</li><li>Manage the group</li><li>Follow emergency procedures</li></ul>`,
                quickCheck: {
                    questions: [
                        { question: 'Which of the following is a possible crisis?', options: ['Theft', 'Shopping', 'Ticket booking', 'Eating snacks'], correct: 0 },
                        { question: 'What is the first priority during a crisis?', options: ['Stay calm', 'Panic', 'Ignore visitors', 'End the tour'], correct: 0 },
                        { question: 'Which skill helps prevent panic during emergencies?', options: ['Composure', 'Aggression', 'Anger', 'Silence'], correct: 0 },
                        { question: 'Crisis management includes natural disasters, medical emergencies, and security issues.', options: ['True', 'False'], correct: 0 },
                        { question: 'Calm and clear communication reduces fear in visitors.', options: ['True', 'False'], correct: 0 }
                    ]
                }
            }
        ],
        brainWarmup: { questions: Array(30).fill(null).map((_, i) => ({ question: `Brain Warm-up Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) },
        endOfModuleEval: { questions: Array(30).fill(null).map((_, i) => ({ question: `Evaluation Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) }
    },
    
    // ============================================
    // MODULE 4: Conclude the Tour (LOCKED)
    // ============================================
    {
        id: 4,
        title: 'Conclude the Tour',
        icon: 'flag-checkered',
        color: 'info',
        intro: `Now that you've mastered the essentials of effective tour information delivery and crisis management, you are ready to accomplish the "Dare to Discover" section! This is your chance to deepen your expertise by exploring the unique stories and highlights of each municipality in Ilocos Norte.

But before you dive into discovering the fascinating facts and stories of each municipality, it's essential to master the last crucial part of the tour: how to properly conclude a tour. A strong conclusion leaves a lasting positive impression on visitors, helps them feel confident and informed, and ensures the entire experience feels complete and professional.

Concluding a tour is not just about wrapping things up—it's about ensuring every visitor feels acknowledged, safe, and prepared for what comes next.`,
        lessons: [
            {
                id: 1,
                title: 'How to Conclude a Tour',
                content: `<h6 class="fw-bold">Key Steps to Follow:</h6>

<ul><li><strong>Gather the Group and Confirm Headcount</strong> - Make sure no one is left behind. Invite final questions.</li>
<li><strong>Provide Clear Location Details</strong> - Remind visitors where they are for independent exploration.</li>
<li><strong>Check Personal Belongings</strong> - Encourage everyone to gather their items.</li>
<li><strong>Manage Tour Feedback and Tips</strong> - Collect surveys, provide tipping envelopes if customary.</li>
<li><strong>Express Gratitude and Say Goodbye</strong> - Thank the driver and group warmly.</li></ul>

<p class="fst-italic mt-3">"Alright everyone, let's gather here for a quick wrap-up. Please make sure you have all your personal items. I'd like to thank our driver for ensuring a safe ride. Thank you for joining us today. Have a wonderful rest of your day!"</p>`,
                quickCheck: {
                    questions: [
                        { question: 'What is the main purpose of concluding a tour?', options: ['To end fast', 'To leave a mark', 'To gather tips', 'To give lectures'], correct: 1 },
                        { question: 'What should guides do first when concluding a tour?', options: ['Check bags', 'Count people', 'Say goodbye', 'Collect tips'], correct: 1 },
                        { question: 'Why is confirming headcount important?', options: ['To check forms', 'To ensure no one is lost', 'To prepare the driver', 'To shorten the tour'], correct: 1 },
                        { question: 'What is collected to improve future tours?', options: ['Tickets', 'Surveys', 'Headcount', 'Bags'], correct: 1 },
                        { question: 'A strong conclusion makes the tour more memorable.', options: ['True', 'False'], correct: 0 }
                    ]
                }
            }
        ],
        brainWarmup: { questions: Array(20).fill(null).map((_, i) => ({ question: `Brain Warm-up Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) },
        endOfModuleEval: { questions: Array(20).fill(null).map((_, i) => ({ question: `Evaluation Q${i+1}`, options: ['A','B','C','D'], correct: 0 })) }
    }
];

// ============================================
// STATE MANAGEMENT
// ============================================
let currentView = 'moduleList';
let currentModuleId = 1;
let currentLessonId = 1;
let currentQuizType = null;
let currentQuestions = [];
let currentQuestionIndex = 0;
let quizScore = 0;
let timerInterval = null;
let timeLeft = 30;

// Progress tracking - Module 1 unlocked, others locked
let moduleProgress = {
    1: { lessonsCompleted: [false, false, false, false], brainWarmupDone: false, evalPassed: false },
    2: { lessonsCompleted: [false, false, false, false], brainWarmupDone: false, evalPassed: false },
    3: { lessonsCompleted: [false, false], brainWarmupDone: false, evalPassed: false },
    4: { lessonsCompleted: [false], brainWarmupDone: false, evalPassed: false }
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadNavUserName();
    loadProgress();
    renderModuleList();
    updateOverallProgress();
});

// ============================================
// VIEW SWITCHING
// ============================================
function showView(viewName) {
    document.querySelectorAll('.view-container').forEach(v => v.classList.remove('active'));
    document.getElementById(viewName + 'View').classList.add('active');
    currentView = viewName;
}

function showModuleList() {
    showView('moduleList');
    renderModuleList();
}

function showLessonList(moduleId = null) {
    if (moduleId) currentModuleId = moduleId;
    const module = modules.find(m => m.id === currentModuleId);
    
    if (!isModuleUnlocked(currentModuleId)) {
        alert(`Module ${currentModuleId} is locked. Complete the previous module's End-of-Module Evaluation first!`);
        return;
    }
    
    document.getElementById('lessonListTitle').textContent = `Module ${currentModuleId}: ${module.title}`;
    document.getElementById('lessonListSubtitle').textContent = `Complete all ${module.lessons.length} lessons to unlock the End-of-Module Evaluation`;
    document.getElementById('breadcrumbModuleName').textContent = `Module ${currentModuleId}`;
    
    renderLessons();
    renderEvaluationCard();
    showView('lessonList');
}

function showLessonContent(lessonId = null) {
    if (lessonId) currentLessonId = lessonId;
    
    if (!isModuleUnlocked(currentModuleId)) {
        alert('This module is locked!');
        return;
    }
    
    if (!isLessonUnlocked(currentModuleId, currentLessonId)) {
        alert('Complete the previous lesson first!');
        return;
    }
    
    const module = modules.find(m => m.id === currentModuleId);
    const lesson = module.lessons.find(l => l.id === currentLessonId);
    
    document.getElementById('breadcrumbModuleBack').textContent = `Module ${currentModuleId}`;
    document.getElementById('breadcrumbLessonName').textContent = lesson.title;
    
    renderLessonContent();
    showView('lessonContent');
}

// ============================================
// RENDERING FUNCTIONS
// ============================================
function renderModuleList() {
    const container = document.getElementById('moduleCardsContainer');
    container.innerHTML = modules.map(module => {
        const unlocked = isModuleUnlocked(module.id);
        const completed = isModuleCompleted(module.id);
        
        return `
            <div class="module-card ${!unlocked ? 'locked' : ''} ${completed ? 'completed' : ''}" 
                 onclick="${unlocked ? `showLessonList(${module.id})` : `alert('Complete Module ${module.id-1} End-of-Module Evaluation first!')`}">
                <div class="d-flex align-items-start">
                    <div class="module-icon bg-${module.color} bg-opacity-10 text-${module.color}">
                        <i class="fas fa-${module.icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="fw-bold mb-2">Module ${module.id}: ${module.title}</h5>
                            <span class="badge bg-${completed ? 'success' : (unlocked ? 'success' : 'secondary')}">
                                ${completed ? 'Completed' : (unlocked ? 'Available' : 'Locked')}
                            </span>
                        </div>
                        <p class="text-muted mb-3">${getModuleDescription(module.id)}</p>
                        <div class="lesson-tags">
                            ${module.lessons.map(l => 
                                `<span class="badge bg-light text-dark me-2 mb-2">${l.title}</span>`
                            ).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderLessons() {
    const module = modules.find(m => m.id === currentModuleId);
    const container = document.getElementById('lessonsContainer');
    const unlocked = isModuleUnlocked(currentModuleId);
    
    let html = `
        <div class="module-intro mb-4">
            <p class="mb-0" style="line-height: 1.8;">${module.intro.replace(/\n\n/g, '</p><p class="mt-3 mb-0" style="line-height: 1.8;">')}</p>
        </div>
    `;
    
    // Brain Warm-up button
    const brainWarmupDone = moduleProgress[currentModuleId]?.brainWarmupDone;
    html += `
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-2">
                        <i class="fas fa-brain text-warning me-2"></i>Brain Warm-up
                    </h5>
                    <p class="text-muted mb-0">Test your prior knowledge before starting the lessons</p>
                </div>
                <button class="btn ${brainWarmupDone ? 'btn-success' : 'btn-brain-warmup'}" 
                        onclick="${unlocked ? 'startBrainWarmup()' : 'alert(\'Module is locked\')'}" 
                        ${brainWarmupDone || !unlocked ? 'disabled' : ''}>
                    <i class="fas fa-${brainWarmupDone ? 'check-circle' : 'play'} me-2"></i>
                    ${brainWarmupDone ? 'Completed' : 'Start Brain Warm-up'}
                </button>
            </div>
        </div>
    `;
    
    // Lessons
    html += `<h5 class="fw-bold mb-3">Lessons</h5>`;
    
    module.lessons.forEach((lesson, index) => {
        const lessonUnlocked = unlocked && isLessonUnlocked(currentModuleId, lesson.id);
        const completed = isLessonCompleted(currentModuleId, lesson.id);
        
        html += `
            <div class="lesson-card ${!lessonUnlocked ? 'locked' : ''} ${completed ? 'completed' : ''}" 
                 onclick="${lessonUnlocked ? `showLessonContent(${lesson.id})` : 'alert(\'Complete previous lesson first!\')'}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Lesson ${index + 1}: ${lesson.title}</h6>
                        <p class="text-muted small mb-0">Quick Check • 5 questions • 90% to pass</p>
                    </div>
                    <div>
                        ${completed ? '<i class="fas fa-check-circle text-success fa-lg"></i>' : 
                          (!lessonUnlocked ? '<i class="fas fa-lock text-secondary"></i>' : 
                          '<i class="fas fa-chevron-right text-muted"></i>')}
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function renderEvaluationCard() {
    const container = document.getElementById('evaluationCardContainer');
    const module = modules.find(m => m.id === currentModuleId);
    const unlocked = isModuleUnlocked(currentModuleId);
    const allLessonsCompleted = moduleProgress[currentModuleId]?.lessonsCompleted.every(c => c);
    const evalPassed = moduleProgress[currentModuleId]?.evalPassed;
    const questionCount = currentModuleId === 1 ? 25 : (currentModuleId === 2 ? 30 : (currentModuleId === 3 ? 30 : 20));
    
    container.innerHTML = `
        <div class="evaluation-card ${!unlocked || !allLessonsCompleted || evalPassed ? 'locked' : ''}" 
             onclick="${unlocked && allLessonsCompleted && !evalPassed ? 'startEndOfModuleEval()' : ''}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-2 text-white">
                        <i class="fas fa-trophy me-2"></i>End-of-Module Evaluation
                    </h5>
                    <p class="mb-0 opacity-90">${questionCount} questions • 90% required to pass and unlock next module</p>
                </div>
                <div>
                    ${evalPassed ? '<i class="fas fa-check-circle fa-2x"></i>' : 
                      (!unlocked ? '<i class="fas fa-lock fa-2x"></i>' :
                      (!allLessonsCompleted ? '<i class="fas fa-lock fa-2x"></i>' : 
                      '<i class="fas fa-chevron-right fa-2x"></i>'))}
                </div>
            </div>
        </div>
    `;
}

function renderLessonContent() {
    const module = modules.find(m => m.id === currentModuleId);
    const lesson = module.lessons.find(l => l.id === currentLessonId);
    const completed = isLessonCompleted(currentModuleId, currentLessonId);
    const unlocked = isModuleUnlocked(currentModuleId);
    
    const container = document.getElementById('lessonContentContainer');
    container.innerHTML = `
        <div class="content-card">
            <h4 class="fw-bold mb-4">${lesson.title}</h4>
            <div class="lesson-content mb-4">
                ${lesson.content}
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-1">Quick Check</h6>
                    <p class="text-muted mb-0">5 questions • 90% required to pass</p>
                </div>
                <button class="btn ${completed ? 'btn-success' : 'btn-mmsu'}" 
                        onclick="${unlocked ? 'startQuickCheck()' : 'alert(\'Module is locked\')'}" 
                        ${completed || !unlocked ? 'disabled' : ''}>
                    <i class="fas fa-${completed ? 'check-circle' : 'play'} me-2"></i>
                    ${completed ? 'Completed' : 'Take Quick Check'}
                </button>
            </div>
        </div>
    `;
}

// ============================================
// HELPER FUNCTIONS
// ============================================
function getModuleDescription(moduleId) {
    const descriptions = {
        1: 'Master pre-tour planning, guest profiling, and supplier coordination',
        2: 'Learn to deliver engaging welcomes, introductions, and tour essentials',
        3: 'Develop storytelling skills and effective information delivery techniques',
        4: 'Perfect your tour wrap-up, feedback collection, and farewell techniques'
    };
    return descriptions[moduleId];
}

function isModuleUnlocked(moduleId) {
    if (moduleId === 1) return true;
    return moduleProgress[moduleId - 1]?.evalPassed || false;
}

function isModuleCompleted(moduleId) {
    return moduleProgress[moduleId]?.evalPassed || false;
}

function isLessonUnlocked(moduleId, lessonId) {
    if (lessonId === 1) return true;
    return moduleProgress[moduleId]?.lessonsCompleted[lessonId - 2] || false;
}

function isLessonCompleted(moduleId, lessonId) {
    return moduleProgress[moduleId]?.lessonsCompleted[lessonId - 1] || false;
}

function updateOverallProgress() {
    const completed = Object.values(moduleProgress).filter(m => m.evalPassed).length;
    document.getElementById('modulesCompleted').textContent = completed;
    document.getElementById('overallFoundationProgress').style.width = (completed / 4) * 100 + '%';
}

function saveProgress() {
    localStorage.setItem('foundationProgress', JSON.stringify(moduleProgress));
}

function loadProgress() {
    const saved = localStorage.getItem('foundationProgress');
    if (saved) {
        moduleProgress = JSON.parse(saved);
    }
}

function loadNavUserName() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('navUserName').textContent = userName;
}

// ============================================
// QUIZ FUNCTIONS (Simplified for prototype)
// ============================================
function startBrainWarmup() {
    const module = modules.find(m => m.id === currentModuleId);
    currentQuizType = 'brain-warmup';
    currentQuestions = module.brainWarmup.questions.slice(0, 5);
    startQuiz('Brain Warm-up');
}

function startQuickCheck() {
    const module = modules.find(m => m.id === currentModuleId);
    const lesson = module.lessons.find(l => l.id === currentLessonId);
    currentQuizType = 'quick-check';
    currentQuestions = lesson.quickCheck.questions;
    startQuiz(`Lesson ${currentLessonId} Quick Check`);
}

function startEndOfModuleEval() {
    const module = modules.find(m => m.id === currentModuleId);
    currentQuizType = 'end-eval';
    currentQuestions = module.endOfModuleEval.questions.slice(0, 5);
    startQuiz('End-of-Module Evaluation');
}

function startQuiz(title) {
    currentQuestionIndex = 0;
    quizScore = 0;
    
    document.getElementById('breadcrumbQuizName').textContent = title;
    document.getElementById('quizTitle').textContent = title;
    
    showQuizQuestion();
    showView('quiz');
}

function showQuizQuestion() {
    const question = currentQuestions[currentQuestionIndex];
    const container = document.getElementById('quizContent');
    
    container.innerHTML = `
        <div class="question-card">
            <p class="mb-3 text-muted">Question ${currentQuestionIndex + 1} of ${currentQuestions.length}</p>
            <h6 class="fw-bold mb-4">${question.question}</h6>
            <div class="options-container">
                ${question.options.map((opt, idx) => `
                    <div class="option-item" onclick="selectOption(${idx})" data-option="${idx}">
                        <span class="fw-bold me-3">${String.fromCharCode(65 + idx)}</span>
                        ${opt}
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    
    startTimer();
}

function selectOption(index) {
    document.querySelectorAll('.option-item').forEach(opt => opt.classList.remove('selected'));
    document.querySelector(`[data-option="${index}"]`).classList.add('selected');
}

function startTimer() {
    timeLeft = 30;
    updateTimerDisplay();
    
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();
        if (timeLeft <= 0) submitQuizAnswer();
    }, 1000);
}

function updateTimerDisplay() {
    const timerEl = document.getElementById('quizTimer');
    timerEl.textContent = `${timeLeft}s`;
    timerEl.classList.toggle('warning', timeLeft <= 10);
}

function submitQuizAnswer() {
    clearInterval(timerInterval);
    
    const selected = document.querySelector('.option-item.selected');
    if (!selected) {
        alert('Please select an answer!');
        startTimer();
        return;
    }
    
    const selectedIndex = parseInt(selected.dataset.option);
    if (selectedIndex === currentQuestions[currentQuestionIndex].correct) {
        quizScore++;
    }
    
    currentQuestionIndex++;
    
    if (currentQuestionIndex < currentQuestions.length) {
        showQuizQuestion();
    } else {
        finishQuiz();
    }
}

function finishQuiz() {
    const percentage = (quizScore / currentQuestions.length) * 100;
    const passed = percentage >= 90;
    
    if (currentQuizType === 'brain-warmup') {
        moduleProgress[currentModuleId].brainWarmupDone = true;
    } else if (currentQuizType === 'quick-check' && passed) {
        const lessonIndex = currentLessonId - 1;
        moduleProgress[currentModuleId].lessonsCompleted[lessonIndex] = true;
    } else if (currentQuizType === 'end-eval' && passed) {
        moduleProgress[currentModuleId].evalPassed = true;
    }
    
    saveProgress();
    showQuizResult(percentage, passed);
}

function showQuizResult(percentage, passed) {
    showView('lessonList');
    
    let message = '';
    if (currentQuizType === 'brain-warmup') {
        message = `Well done—you've taken the first step! Your total score is ${quizScore}/${currentQuestions.length}.`;
    } else if (currentQuizType === 'quick-check') {
        message = passed ? 
            'Great job! You\'ve mastered this lesson and are ready to proceed to the next.' :
            'Almost there! Review the material again to strengthen your understanding.';
    } else if (currentQuizType === 'end-eval') {
        message = passed ?
            'Awesome! You crushed this module. Ready to level up? Let\'s move on to the next challenge!' :
            'Almost nailed it! Take another look and talk with your facilitator.';
    }
    
    alert(`${message}\nScore: ${quizScore}/${currentQuestions.length} (${Math.round(percentage)}%)`);
    
    renderLessons();
    renderEvaluationCard();
    updateOverallProgress();
    renderModuleList();
}

// Event listener for submit button
document.addEventListener('click', function(e) {
    if (e.target.id === 'submitQuizBtn') {
        submitQuizAnswer();
    }
});