<script setup>
import { watch, ref } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  languages: {
    type: Array,
    required: true,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const { locale } = useI18n({ useScope: 'global' })

// اقرأ اللغة المحفوظة عند بداية الصفحة
const savedLang = localStorage.getItem('lang')
if (savedLang) {
  locale.value = savedLang
}

// دالة لتغيير اللغة وتخزينها
function changeLanguage(lang) {
  if (locale.value !== lang) {
    locale.value = lang
    localStorage.setItem('lang', lang)
  }
}
</script>

<template>
  <IconBtn>
    <VIcon icon="tabler-language" />

    <!-- Menu -->
    <VMenu
      activator="parent"
      :location="props.location"
      offset="12px"
      width="175"
    >
      <!-- List -->
      <VList :selected="[locale]" color="primary">
        <!-- List item -->
        <VListItem
          v-for="lang in props.languages"
          :key="lang.i18nLang"
          :value="lang.i18nLang"
          @click="changeLanguage(lang.i18nLang)"
        >
          <!-- Language label -->
          <VListItemTitle>
            {{ lang.label }}
          </VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </IconBtn>
</template>
