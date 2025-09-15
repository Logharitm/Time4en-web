<script setup>
import { useTheme } from 'vuetify'
import joinArrow from '@images/front-pages/icons/Join-community-arrow.png'

// صور الهيرو للSlider
import heroImg1 from '@images/front-pages/landing-page/Banner-2.jpg'
import heroImg2 from '@images/front-pages/landing-page/PEHome-Banner-1920.png'
import heroImg3 from '@images/front-pages/landing-page/img_1.png'

const theme = useTheme()
const heroImages = [heroImg1, heroImg2, heroImg3]
</script>

<template>
  <div id="home">
    <!-- Hero Section مع الخلفية الأصلية -->
    <div
      class="landing-hero"
      :class="theme.current.value.dark ? 'landing-hero-dark-bg' : 'landing-hero-light-bg'"
    >
      <VContainer>
        <div class="hero-text-box text-center px-6">
          <h1 class="hero-title mb-4">تعلم الإنجليزية بسهولة وفي أي وقت مع Time4EN</h1>
          <div class="position-relative">
            <h6 class="position-absolute hero-btn-item d-md-flex d-none text-h6 text-medium-emphasis">
              {{ $t("Join Community") }}
              <VImg :src="joinArrow" class="flip-in-rtl" width="54" height="31" />
            </h6>
            <VBtn
              :size="$vuetify.display.smAndUp ? 'large' : 'default'"
              :to="{ name: 'home', hash: `#pricing-plan` }"
            >
              {{ $t("Get early Access") }}
            </VBtn>
          </div>
        </div>
      </VContainer>
    </div>

    <!-- Slider الهيرو فوق الخلفية -->
    <VContainer style="margin-top: -264px !important;">
      <VCarousel
        cycle
        hide-delimiters
        height="400"
        class="my-12"
      >
        <VCarouselItem
          v-for="(img, index) in heroImages"
          :key="index"
        >
          <RouterLink :to="{ name: 'dashboards-ecommerce' }" target="_blank">
            <VImg
              :src="img"
              class="hero-slider-img"
              cover
            />
          </RouterLink>
        </VCarouselItem>
      </VCarousel>
    </VContainer>
  </div>
</template>

<style lang="scss" scoped>
/* الاحتفاظ بالتدرجات والألوان الأصلية */
.landing-hero-dark-bg {
  background-color: #25293c;
  background-image: url("@images/front-pages/backgrounds/hero-bg.png");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.landing-hero-light-bg {
  background: url("@images/front-pages/backgrounds/hero-bg.png") center no-repeat,
  linear-gradient(138.18deg, #eae8fd 0%, #fce5e6 94.44%);
  background-size: cover;
}

.landing-hero {
  border-radius: 0 0 50px 50px;
  padding-block: 9.75rem 22rem;
  position: relative;
  overflow: hidden;
}

.hero-slider-img {
  border-radius: 20px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hero-text-box {
  max-width: 38rem;
  margin-inline: auto;
}

.hero-title {
  font-size: 42px;
  font-weight: 800;
  line-height: 48px;
  background: linear-gradient(135deg, #28c76f 0%, #5a4aff 47.92%, #ff3739 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-btn-item {
  inset-block-start: 80%;
  inset-inline-start: 5%;
}
</style>
