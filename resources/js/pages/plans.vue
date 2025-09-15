<script setup>
import {ref, onMounted} from 'vue'

definePage({
  meta: {
    action: 'read',
    subject: 'Home',
  },
})

// صور الباقات
import safeBoxWithGoldenCoin from '@images/misc/3d-safe-box-with-golden-dollar-coins.png'
import spaceRocket from '@images/misc/3d-space-rocket-with-smoke.png'
import dollarCoinPiggyBank from '@images/misc/dollar-coins-flying-pink-piggy-bank.png'

const pricingPlans = ref([])
const faqs = ref([
  {
    question: 'هل أستطيع إلغاء الاشتراك في أي وقت؟',
    answer: 'نعم، يمكنك إلغاء الاشتراك متى شئت من خلال لوحة التحكم الخاصة بك.',
  },
  {
    question: 'هل يوجد فترة تجريبية مجانية؟',
    answer: 'نعم، يمكنك البدء بالباقة المجانية لتجربة جميع المميزات الأساسية.',
  },
  {
    question: 'هل يمكنني الترقية من باقة لأخرى؟',
    answer: 'بكل تأكيد، يمكنك الترقية في أي وقت وسيتم احتساب المبلغ المتبقي تلقائيًا.',
  },
])

const steps = ref([
  {
    title: 'اختر الباقة',
    description: 'قم بمراجعة الباقات المتاحة واختيار الأنسب لك.',
    icon: 'mdi-package-variant-closed', // مثال أيقونة
  },
  {
    title: 'اكمل الاشتراك',
    description: 'املأ بياناتك وادفع أو فعّل الباقة المجانية.',
    icon: 'mdi-credit-card-check-outline',
  },
  {
    title: 'ابدأ باستخدام المميزات',
    description: 'استفد من جميع الأدوات والمزايا فورًا بعد التفعيل.',
    icon: 'mdi-rocket-launch',
  },
])

const testimonials = ref([
  {
    name: 'أحمد علي',
    role: 'رائد أعمال',
    quote: 'منصة ممتازة، ساعدتني على إطلاق مشروعي بسرعة سهلة وبسلاسة.',
    avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
  },
  {
    name: 'سارة محمد',
    role: 'مطور محتوى',
    quote: 'عدد الكلمات والمزايا تناسب احتياجاتي تمامًا وكانت التجربة رائعة.',
    avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
  },
])

// نموذج التواصل
const contactForm = ref({
  name: '',
  email: '',
  message: '',
})

const sendMessage = () => {
  console.log('📩 الرسالة المرسلة:', contactForm.value)
  // تقدر هنا تبعت للـ API أو تعمل إشعار نجاح
}

onMounted(async () => {
  try {
    const response = await $api('/plans')
    if (response?.status === 'success') {
      pricingPlans.value = response.data.map((plan, index) => ({
        id: plan.id,
        name: plan.name,
        description: plan.description,
        monthlyPrice: parseFloat(plan.price),
        yearlyPrice: parseFloat(plan.price) * plan.duration_months,
        duration: plan.duration_months,
        wordsLimit: plan.words_limit,
        current: plan.price === "0.00",
        isPopular: index === 1,
        logo: [dollarCoinPiggyBank, safeBoxWithGoldenCoin, spaceRocket][index % 3],
      }))
    }
  } catch (error) {
    console.error('خطأ في جلب الباقات:', error)
  }
})
</script>

<template>
  <div class="pricing-landing">
    <!-- Hero Section -->
    <section class="hero text-center py-16">
      <h1 class="text-h2 font-weight-bold mb-4">اختر خطتك المثالية</h1>
      <p class="text-body-1 mb-6">
        جميع الخطط تتضمن أكثر من 40 أداة متقدمة لمساعدتك على تطوير عملك 🚀
      </p>
      <VBtn color="primary" size="large" rounded="lg">ابدأ الآن</VBtn>
    </section>

    <!-- Plans Section -->
    <section class="plans py-12">
      <VContainer>
        <VRow>
          <VCol
            v-for="plan in pricingPlans"
            :key="plan.id"
            cols="12"
            sm="6"
            md="4"
          >
            <VCard
              elevation="6"
              class="plan-card text-center pa-6"
              :class="plan.isPopular ? 'highlighted' : ''"
            >
              <VImg
                :src="plan.logo"
                :height="100"
                contain
                class="mx-auto mb-4"
              />

              <h3 class="text-h4 mb-2">{{ plan.name }}</h3>
              <p class="text-body-2 mb-4">{{ plan.description }}</p>

              <div class="price-box mb-6">
                <span class="price">{{ plan.monthlyPrice }}</span>
              </div>

              <VList density="compact" class="mb-6">
                <VListItem prepend-icon="tabler-check">
                  <VListItemTitle>مدة: {{ plan.duration }} شهر</VListItemTitle>
                </VListItem>
                <VListItem prepend-icon="tabler-check">
                  <VListItemTitle>عدد الكلمات: {{ plan.wordsLimit }}</VListItemTitle>
                </VListItem>
              </VList>

              <VBtn
                block
                rounded="lg"
                size="large"
                :color="plan.current ? 'success' : 'primary'"
                :variant="plan.isPopular ? 'elevated' : 'tonal'"
              >
                {{ plan.monthlyPrice === 0 ? 'خطة حالية' : 'اشترك الآن' }}
              </VBtn>
            </VCard>
          </VCol>
        </VRow>
      </VContainer>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works py-12 bg-light">
      <VContainer>
        <h2 class="text-h4 text-center mb-8">كيفية العمل</h2>
        <VRow>
          <VCol
            v-for="(step, idx) in steps"
            :key="idx"
            cols="12" md="4"
            class="text-center mb-6"
          >
            <VIcon class="mb-4" size="48">{{ step.icon }}</VIcon>
            <h3 class="text-h5 mb-2">{{ step.title }}</h3>
            <p class="text-body-2 text-disabled">{{ step.description }}</p>
          </VCol>
        </VRow>
      </VContainer>
    </section>

    <!-- Parallax Section -->
    <section class="parallax-section">
      <div class="parallax-bg">
        <VImg
          src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1600&q=80"
          class="parallax-image"
          cover
        />
        <div class="parallax-overlay d-flex flex-column align-center justify-center text-center px-6">
          <h2 class="text-h3 font-weight-bold mb-4 text-white">التعلم رحلة ممتعة 🚀</h2>
          <p class="text-body-1 text-white mb-6">
            استمتع بتجربة تعليمية فريدة، حيث تجمع منصتنا بين التقنية والمحتوى المميز.
          </p>
          <VBtn color="secondary" size="large" rounded="lg">ابدأ الآن</VBtn>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-12">
      <VContainer>
        <h2 class="text-h4 text-center mb-6">الأسئلة الشائعة</h2>
        <VExpansionPanels variant="accordion">
          <VExpansionPanel
            v-for="(faq, index) in faqs"
            :key="index"
          >
            <VExpansionPanelTitle>{{ faq.question }}</VExpansionPanelTitle>
            <VExpansionPanelText>{{ faq.answer }}</VExpansionPanelText>
          </VExpansionPanel>
        </VExpansionPanels>
      </VContainer>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-12">
      <VContainer>
        <h2 class="text-h4 text-center mb-8">ماذا يقول عملاؤنا</h2>
        <VRow>
          <VCol v-for="(t, idx) in testimonials" :key="idx" cols="12" md="6">
            <VCard elevation="3" class="pa-6 d-flex align-center">
              <VAvatar size="64" class="me-4">
                <img :src="t.avatar" alt="avatar"/>
              </VAvatar>
              <div>
                <p class="text-body-1 mb-2">“{{ t.quote }}”</p>
                <p class="text-subtitle-2 font-weight-medium">{{ t.name }} - {{ t.role }}</p>
              </div>
            </VCard>
          </VCol>
        </VRow>
      </VContainer>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-12">
      <VContainer>
        <h2 class="text-h4 text-center mb-6">راسلنا</h2>
        <VRow justify="center">
          <VCol cols="12" md="12">
            <VCard elevation="6" class="pa-6">
              <VForm @submit.prevent="sendMessage">
                <VTextField
                  v-model="contactForm.name"
                  label="الاسم"
                  outlined
                  class="mb-4"
                  required
                />
                <VTextField
                  v-model="contactForm.email"
                  label="البريد الإلكتروني"
                  outlined
                  class="mb-4"
                  required
                />
                <VTextarea
                  v-model="contactForm.message"
                  label="الرسالة"
                  outlined
                  rows="4"
                  required
                />
                <VBtn
                  type="submit"
                  color="primary"
                  block
                  class="mt-4"
                  size="large"
                >
                  إرسال الرسالة
                </VBtn>
              </VForm>
            </VCard>
          </VCol>
        </VRow>
      </VContainer>
    </section>

  </div>
</template>

<style lang="scss" scoped>
.pricing-landing {
  min-height: 100vh;
}

.hero {
  max-width: 700px;
  margin: auto;
}

.plan-card {
  border-radius: 20px;
  transition: all 0.3s ease;
}

.plan-card:hover {
  transform: translateY(-8px);
}

.highlighted {
  border: 2px solid var(--v-primary-base);
}

.price-box {
  font-size: 2rem;
  font-weight: bold;
  color: var(--v-theme-primary);
}

.price {
  font-size: 2.5rem;
}

/* How It Works Section */
.bg-light {
  background-color: var(--v-theme-surface); /* أو لون فاتح حسب التصميم */
}

/* Parallax Section */
.parallax-section {
  position: relative;
  height: 400px;
  overflow: hidden;
  margin: 60px 0;
  border-radius: 20px;
}

.parallax-bg {
  position: relative;
  height: 100%;
  width: 100%;
}

.parallax-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 120%;
  object-fit: cover;
  animation: parallaxMove 10s ease-in-out infinite alternate;
}

.parallax-overlay {
  position: relative;
  z-index: 2;
  height: 100%;
  background: rgba(0, 0, 0, 0.45);
  border-radius: 20px;
}

@keyframes parallaxMove {
  from {
    transform: translateY(0);
  }
  to {
    transform: translateY(-40px);
  }
}

.testimonials-section {
  background-color: var(--v-theme-surface);
}

</style>
