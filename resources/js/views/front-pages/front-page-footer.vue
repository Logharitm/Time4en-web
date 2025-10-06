<script setup>

</script>

<template>
  <div class="footer">
    <div class="footer-line w-100">
      <VContainer>
        <div class="d-flex justify-space-between flex-wrap gap-y-5 align-center">
          <div class="text-body-1 text-white-variant text-wrap me-4">



            تصميم وبرمجة
            <a
              href="https://logharitm.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="font-weight-bold ms-1 text-white"
            >
              Logharitm
            </a>
            &copy;
            {{ new Date().getFullYear() }}
          </div>

          <div class="d-flex gap-x-6">
            <template
              v-for="(item, index) in [
                { title: 'github', icon: 'tabler-brand-github-filled', href: 'https://github.com/pixinvent' },
                { title: 'facebook', icon: 'tabler-brand-facebook-filled', href: 'https://www.facebook.com/pixinvents/' },
                { title: 'twitter', icon: 'tabler-brand-twitter-filled', href: 'https://twitter.com/pixinvents' },
                { title: 'google', icon: 'tabler-brand-youtube-filled', href: 'https://www.youtube.com/channel/UClOcB3o1goJ293ri_Hxpklg' },
              ]"
              :key="index"
            >
              <a
                :href="item.href"
                target="_blank"
                rel="noopener noreferrer"
              >
                <VIcon
                  :icon="item.icon"
                  size="16"
                  color="white"
                />
              </a>
            </template>
          </div>
        </div>
      </VContainer>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.footer-title {
  color: rgba(255, 255, 255, 92%);
}

.footer-top {
  border-radius: 60px 60px 0 0;
  background-size: cover;
  color: #fff;
}

.footer-links {
  .text-white-variant,
  .text-body-1 {
    &:hover {
      color: #fff;
    }
  }
}

.footer-line {
  background: #451291;
}
</style>

<style lang="scss">
.subscribe-form {
  .v-label {
    color: rgba(225, 222, 245, 90%) !important;
  }

  .v-field {
    border-end-end-radius: 0;
    border-end-start-radius: 10px;
    border-start-end-radius: 0;
    border-start-start-radius: 10px;

    input.v-field__input::placeholder {
      color: rgba(225, 222, 245, 40%) !important;
    }

    input.v-field__input {
      color: rgba(255, 255, 255, 78%);
    }
  }
}

.footer {
  border-radius: 50%;

  @media (min-width: 600px) and (max-width: 960px) {
    .v-container {
      padding-inline: 2rem !important;
    }

    .footer-logo-buttons {
      gap: 0.75rem;
    }
  }
}
</style>
