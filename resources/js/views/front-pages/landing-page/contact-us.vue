<script setup>
import ConnectImg from '@images/front-pages/landing-page/contact-us.jpg'
import { ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()

const name = ref('')
const email = ref('')
const subject = ref('')
const message = ref('')
const contactInfo = ref(null)
const loading = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('success')

// ✅ جلب بيانات الاتصال
onMounted(async () => {
  try {
    const res = await $api('/contact-info')
    if (res.status === 'success') {
      contactInfo.value = res.data
    }
  } catch (error) {
    console.error(error)
  }
})

// ✅ العنوان حسب اللغة
const contactAddress = computed(() => {
  if (!contactInfo.value) return ''
  return locale.value === 'en' ? contactInfo.value.address_en : contactInfo.value.address
})

// ✅ إرسال الرسالة
const sendMessage = async () => {
  loading.value = true
  try {
    const res = await $api('/messages', {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        subject: subject.value,
        message: message.value,
      },
    })

    if (res.status === 'success') {
      snackbarMessage.value = res.message
      snackbarColor.value = 'success'
      snackbar.value = true

      // إعادة تعيين الحقول
      name.value = ''
      email.value = ''
      subject.value = ''
      message.value = ''
    } else {
      snackbarMessage.value = res.message || 'حدث خطأ ما'
      snackbarColor.value = 'error'
      snackbar.value = true
    }
  } catch (error) {
    snackbarMessage.value = 'فشل الاتصال بالخادم'
    snackbarColor.value = 'error'
    snackbar.value = true
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <VContainer id="contact-us">
    <!-- 👉 العناوين -->
    <div class="contact-us-section">
      <div class="headers d-flex justify-center flex-column align-center pb-16">
        <VChip label color="primary" class="mb-4" size="small">
          {{ t('contact.chip') }}
        </VChip>
        <h4 class="d-flex align-center text-h4 mb-1 flex-wrap justify-center">
          {{ t('contact.title') }}
        </h4>
        <p class="text-body-1 mb-0">
          {{ t('contact.subtitle') }}
        </p>
      </div>

      <div class="mb-15">
        <VRow class="match-height">
          <!-- 👉 بيانات التواصل -->
          <VCol cols="12" md="5">
            <div class="contact-card h-100">
              <VCard
                variant="outlined"
                border
                class="pa-2"
                :style="{ borderRadius: '3.75rem 0.375rem 0.375rem 0.375rem' }"
              >
                <VImg
                  :src="ConnectImg"
                  :style="{ borderRadius: '3.75rem 0.375rem 0.375rem 0.375rem' }"
                />
                <VCardText class="pa-4 pb-1">
                  <div v-if="contactInfo" class="d-flex flex-column gap-y-4">
                    <!-- ايميل -->
                    <div class="d-flex gap-x-3 align-center">
                      <VAvatar size="36" color="primary" variant="tonal" class="rounded-sm">
                        <VIcon icon="tabler-mail" size="24" />
                      </VAvatar>
                      <div>
                        <div class="text-body-1">{{ t('contact.info.email') }}</div>
                        <h6 class="text-h6">{{ contactInfo.email }}</h6>
                      </div>
                    </div>

                    <!-- هاتف -->
                    <div class="d-flex gap-x-3 align-center">
                      <VAvatar size="36" color="primary" variant="tonal" class="rounded-sm">
                        <VIcon icon="tabler-phone-call" size="24" />
                      </VAvatar>
                      <div>
                        <div class="text-body-1">{{ t('contact.info.phone') }}</div>
                        <h6 class="text-h6">{{ contactInfo.phone }}</h6>
                      </div>
                    </div>

                    <!-- العنوان -->
                    <div class="d-flex gap-x-3 align-center">
                      <VAvatar size="36" color="primary" variant="tonal" class="rounded-sm">
                        <VIcon icon="tabler-map-pin" size="24" />
                      </VAvatar>
                      <div>
                        <div class="text-body-1">{{ t('contact.info.address') }}</div>
                        <h6 class="text-h6">{{ contactAddress }}</h6>
                      </div>
                    </div>

                    <!-- واتساب -->
                    <div class="d-flex gap-x-3 align-center">
                      <VAvatar size="36" color="primary" variant="tonal" class="rounded-sm">
                        <VIcon icon="tabler-brand-whatsapp" size="24" />
                      </VAvatar>
                      <div>
                        <div class="text-body-1">{{ t('contact.info.whatsapp') }}</div>
                        <h6 class="text-h6">{{ contactInfo.whatsapp }}</h6>
                      </div>
                    </div>

                    <!-- فيسبوك -->
                    <div class="d-flex gap-x-3 align-center">
                      <VAvatar size="36" color="primary" variant="tonal" class="rounded-sm">
                        <VIcon icon="tabler-brand-facebook" size="24" />
                      </VAvatar>
                      <div>
                        <div class="text-body-1">{{ t('contact.info.facebook') }}</div>
                        <h6 class="text-h6">
                          <a :href="contactInfo.facebook" target="_blank">{{ t('contact.info.visit') }}</a>
                        </h6>
                      </div>
                    </div>
                  </div>
                </VCardText>
              </VCard>
            </div>
          </VCol>

          <!-- 👉 نموذج المراسلة -->
          <VCol cols="12" md="7">
            <VCard>
              <VCardItem class="pb-0">
                <h4 class="text-h4 mb-1">
                  {{ t('contact.send_title') }}
                </h4>
              </VCardItem>

              <VCardText>
                <p class="mb-6">{{ t('contact.send_subtitle') }}</p>
                <VForm @submit.prevent="sendMessage">
                  <VRow>
                    <VCol cols="12" md="6">
                      <AppTextField
                        v-model="name"
                        :label="t('contact.form.name')"
                        :placeholder="t('contact.form.name_placeholder')"
                      />
                    </VCol>

                    <VCol cols="12" md="6">
                      <AppTextField
                        v-model="email"
                        :label="t('contact.form.email')"
                        placeholder="example@email.com"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextField
                        v-model="subject"
                        :label="t('contact.form.subject')"
                        :placeholder="t('contact.form.subject_placeholder')"
                      />
                    </VCol>

                    <VCol cols="12">
                      <AppTextarea
                        v-model="message"
                        :label="t('contact.form.message')"
                        :placeholder="t('contact.form.message_placeholder')"
                        rows="13"
                      />
                    </VCol>

                    <VCol>
                      <VBtn :loading="loading" type="submit">
                        {{ t('contact.form.submit') }}
                      </VBtn>
                    </VCol>
                  </VRow>
                </VForm>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>
      </div>
    </div>

    <!-- ✅ Snackbar -->
    <VSnackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
      {{ snackbarMessage }}
    </VSnackbar>
  </VContainer>
</template>

<style lang="scss" scoped>
.contact-us-section {
  margin-block: 5.25rem;
}

.section-title {
  font-size: 24px;
  font-weight: 800;
  line-height: 36px;
}

.section-title::after {
  position: absolute;
  background: url("../../../assets/images/front-pages/icons/section-title-icon.png") no-repeat left bottom;
  background-size: contain;
  block-size: 100%;
  content: "";
  font-weight: 800;
  inline-size: 120%;
  inset-block-end: 12%;
  inset-inline-start: -12%;
}

.contact-card {
  position: relative;
}

.contact-card::before {
  position: absolute;
  content: url("@images/front-pages/icons/contact-border.png");
  inset-block-start: -2.5rem;
  inset-inline-start: -2.5rem;
}

@media screen and (max-width: 999px) {
  .contact-card::before {
    display: none;
  }
}
</style>
