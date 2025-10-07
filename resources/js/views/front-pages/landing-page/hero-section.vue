<script setup>
import { useTheme } from 'vuetify'
import joinArrow from '@images/front-pages/icons/Join-community-arrow.png'

// صور الهيرو للSlider
import heroImg1 from '@images/front-pages/landing-page/banner.png'

const theme = useTheme()
const heroImages = [heroImg1]
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
          <h1 class="hero-title mb-5">
            تعلم الإنجليزية بسهولة
          </h1>
          <h1 class="hero-title mb-4">
            وفي أي وقت مع Time4EN
          </h1>
          <div class="position-relative">
            <h6 class="position-absolute hero-btn-item d-md-flex d-none text-h6 text-medium-emphasis" />
          </div>
        </div>
      </VContainer>
    </div>

    <!-- Slider الهيرو فوق الخلفية -->
    <VContainer style="margin-top: -364px !important;">
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
          <VImg
            :src="img"
            class="hero-slider-img"
            cover
          />
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
  background: #451291;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-btn-item {
  inset-block-start: 80%;
  inset-inline-start: 5%;
}
</style>
