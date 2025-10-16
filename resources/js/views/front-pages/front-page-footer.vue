<script setup>
import { ref, onMounted, computed } from 'vue'

const contactInfo = ref(null)
const isLoading = ref(true)

const fetchContactInfo = async () => {
  isLoading.value = true
  try {
    const res = await $api('/contact-info', { method: 'GET' })

    contactInfo.value = res?.data || null
  } catch (err) {
    console.error('Error fetching contact info:', err)
  } finally {
    isLoading.value = false
  }
}

const contactAddress = computed(() => {
  if (!contactInfo.value) return ''

  return locale.value === 'en' ? contactInfo.value.address_en : contactInfo.value.address
})

onMounted(() => {
  fetchContactInfo()
})
</script>

<template>
  <footer class="footer">
    <VContainer class="footer-container py-10">
      <VRow class="gap-y-6">
        <!-- 👈 About & Design -->
        <VCol
          cols="12"
          md="4"
        >
          <h4 class="footer-title mb-3">
            {{ $t('prog') }}
          </h4>
          <p class="text-white-variant">
            {{ $t('by') }}
            <a
              href="https://logharitm.com/"
              target="_blank"
              class="text-white font-weight-bold ms-1"
            >
              {{ $t('Logharitm') }}
            </a>
          </p>
          <p class="text-white-variant mt-2">
            &copy; {{ new Date().getFullYear() }} {{ $t('copyrights') }}
          </p>
        </VCol>

        <!-- 👈 Contact Info -->
        <VCol
          v-if="contactInfo"
          cols="12"
          md="4"
        >
          <h4 class="footer-title mb-3">
            {{ $t('contact_info') }}
          </h4>
          <p class="text-white-variant mb-1">
            {{ $t('address') }}   : {{ contactAddress }}
          </p>
          <p class="text-white-variant mb-1">
            {{ $t('phone') }} : {{ contactInfo.phone }}
          </p>
          <p class="text-white-variant mb-1">
            {{ $t('email') }}  : {{ contactInfo.email }}
          </p>
        </VCol>

        <!-- 👈 Social Media -->
        <VCol
          cols="12"
          md="4"
        >
          <h4 class="footer-title mb-3">
            {{ $t('followus') }}
          </h4>
          <div class="d-flex gap-4">
            <template v-if="contactInfo">
              <a
                v-if="contactInfo.facebook"
                :href="contactInfo.facebook"
                target="_blank"
              >
                <VIcon
                  icon="tabler-brand-facebook-filled"
                  size="20"
                  color="white"
                />
              </a>
              <a
                v-if="contactInfo.twitter"
                :href="contactInfo.twitter"
                target="_blank"
              >
                <VIcon
                  icon="tabler-brand-twitter-filled"
                  size="20"
                  color="white"
                />
              </a>
              <a
                v-if="contactInfo.instagram"
                :href="contactInfo.instagram"
                target="_blank"
              >
                <VIcon
                  icon="tabler-brand-instagram-filled"
                  size="20"
                  color="white"
                />
              </a>
              <a
                v-if="contactInfo.whatsapp"
                :href="`https://wa.me/${contactInfo.whatsapp}`"
                target="_blank"
              >
                <VIcon
                  icon="tabler-brand-whatsapp-filled"
                  size="20"
                  color="white"
                />
              </a>
            </template>

            <template v-else>
              <VIcon
                icon="tabler-brand-facebook-filled"
                size="20"
                color="white"
              />
              <VIcon
                icon="tabler-brand-twitter-filled"
                size="20"
                color="white"
              />
              <VIcon
                icon="tabler-brand-instagram-filled"
                size="20"
                color="white"
              />
            </template>
          </div>
        </VCol>
      </VRow>
    </VContainer>
  </footer>
</template>

<style lang="scss" scoped>
.footer {
  background-color: #451291;
  color: #fff;
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;

  .footer-container {
    display: flex;
    flex-direction: column;
  }

  .footer-title {
    font-weight: bold;
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
  }

  p.text-white-variant {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin: 0;
  }

  a {
    text-decoration: none;
    transition: color 0.3s ease;
    &:hover {
      color: #f0f0f0;
    }
  }

  .d-flex.gap-4 a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transition: background 0.3s ease;
    &:hover {
      background: rgba(255, 255, 255, 0.2);
    }
  }
}

@media (max-width: 768px) {
  .footer {
    border-radius: 0;
    .footer-container {
      text-align: center;
      .d-flex.gap-4 {
        justify-content: center;
      }
    }
  }
}
</style>
