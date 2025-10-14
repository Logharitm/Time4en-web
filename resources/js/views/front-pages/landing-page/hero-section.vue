<script setup>
import { useTheme } from 'vuetify'
const theme = useTheme()
</script>

<template>
  <div id="home">
    <div
      class="landing-hero"
      :class="theme.current.value.dark ? 'landing-hero-dark-bg' : 'landing-hero-light-bg'"
    >
      <VContainer>
        <div class="hero-text-box text-center px-6">
          <h1 class="hero-title mb-5">
            {{ $t('learn_easy')}}
          </h1>
          <h1 class="hero-title mb-4">
            {{ $t('using_app') }}
          </h1>
          <div class="position-relative">
            <h6 class="position-absolute hero-btn-item d-md-flex d-none text-h6 text-medium-emphasis" />
          </div>
        </div>
      </VContainer>
    </div>
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
  padding-top: 15rem;
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
