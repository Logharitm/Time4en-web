<script setup>
import { ref, onMounted } from 'vue'

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

onMounted(() => {
  fetchContactInfo()
})
</script>

<template>
  <footer class="footer">
    <VContainer class="footer-container py-10">
      <VRow class="gap-y-6">
        <!-- 👈 About & Design -->
        <VCol cols="12" md="4">
          <h4 class="footer-title mb-3">تصميم وبرمجة</h4>
          <p class="text-white-variant">
            بواسطة
            <a href="https://logharitm.com/" target="_blank" class="text-white font-weight-bold ms-1">
              Logharitm
            </a>
          </p>
          <p class="text-white-variant mt-2">&copy; {{ new Date().getFullYear() }} جميع الحقوق محفوظة</p>
        </VCol>

        <!-- 👈 Contact Info -->
        <VCol cols="12" md="4" v-if="contactInfo">
          <h4 class="footer-title mb-3">معلومات الاتصال</h4>
          <p class="text-white-variant mb-1">العنوان: {{ contactInfo.address }}</p>
          <p class="text-white-variant mb-1">الهاتف: {{ contactInfo.phone }}</p>
          <p class="text-white-variant mb-1">البريد الإلكتروني: {{ contactInfo.email }}</p>
        </VCol>

        <!-- 👈 Social Media -->
        <VCol cols="12" md="4">
          <h4 class="footer-title mb-3">تابعنا</h4>
          <div class="d-flex gap-4">
            <template v-if="contactInfo">
              <a v-if="contactInfo.facebook" :href="contactInfo.facebook" target="_blank">
                <VIcon icon="tabler-brand-facebook-filled" size="20" color="white" />
              </a>
              <a v-if="contactInfo.twitter" :href="contactInfo.twitter" target="_blank">
                <VIcon icon="tabler-brand-twitter-filled" size="20" color="white" />
              </a>
              <a v-if="contactInfo.instagram" :href="contactInfo.instagram" target="_blank">
                <VIcon icon="tabler-brand-instagram-filled" size="20" color="white" />
              </a>
              <a v-if="contactInfo.whatsapp" :href="`https://wa.me/${contactInfo.whatsapp}`" target="_blank">
                <VIcon icon="tabler-brand-whatsapp-filled" size="20" color="white" />
              </a>
            </template>

            <template v-else>
              <VIcon icon="tabler-brand-facebook-filled" size="20" color="white" />
              <VIcon icon="tabler-brand-twitter-filled" size="20" color="white" />
              <VIcon icon="tabler-brand-instagram-filled" size="20" color="white" />
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
  border-top-left-radius: 50px;
  border-top-right-radius: 50px;

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
