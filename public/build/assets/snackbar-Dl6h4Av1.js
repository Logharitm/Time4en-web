import{r as d,c as x,o as B,b as a,e,s as n,a5 as k,$ as m,l as b,Z as v,F as T,f as w,d as u}from"./main-DrcHKvvT.js";import{V as S}from"./VSnackbar-CvzokaOU.js";import{_ as C}from"./AppCardCode-CXAbC5Y5.js";import{V as $,a as _}from"./VRow-DRCd4e6m.js";import"./VOverlay-Bg9CyQMQ.js";import"./easing-Bybner-F.js";import"./delay-rfspCLr3.js";import"./lazy-ByFeHplP.js";import"./scopeId-CvdluoJ6.js";import"./VImg-BM6eIrEi.js";import"./forwardRefs-C-GTDzx5.js";import"./vue3-perfect-scrollbar-CpC-TV4z.js";import"./VCard-CwmXGZq3.js";import"./VAvatar-3hKLDrM6.js";import"./VCardText-D5solSbX.js";import"./VDivider-Ciys2j2S.js";/* empty css              */const O={class:"demo-space-x"},D={__name:"DemoSnackbarTransition",setup(I){const o=d(!1),s=d(!1),t=d(!1);return(r,c)=>(B(),x("div",O,[a(k,{onClick:c[0]||(c[0]=f=>o.value=!0)},{default:e(()=>c[6]||(c[6]=[n(" fade snackbar ")])),_:1,__:[6]}),a(S,{modelValue:b(o),"onUpdate:modelValue":c[1]||(c[1]=f=>m(o)?o.value=f:null),transition:"fade-transition",location:"top start"},{default:e(()=>c[7]||(c[7]=[n(" I'm a fade transition snackbar. ")])),_:1,__:[7]},8,["modelValue"]),a(k,{onClick:c[2]||(c[2]=f=>s.value=!0)},{default:e(()=>c[8]||(c[8]=[n(" Scale snackbar ")])),_:1,__:[8]}),a(S,{modelValue:b(s),"onUpdate:modelValue":c[3]||(c[3]=f=>m(s)?s.value=f:null),transition:"scale-transition",location:"bottom end"},{default:e(()=>c[9]||(c[9]=[n(" I'm a scale transition snackbar. ")])),_:1,__:[9]},8,["modelValue"]),a(k,{onClick:c[4]||(c[4]=f=>t.value=!0)},{default:e(()=>c[10]||(c[10]=[n(" scroll y reverse ")])),_:1,__:[10]}),a(S,{modelValue:b(t),"onUpdate:modelValue":c[5]||(c[5]=f=>m(t)?t.value=f:null),transition:"scroll-y-reverse-transition",location:"top end"},{default:e(()=>c[11]||(c[11]=[n(" I'm a scroll y reverse transition snackbar. ")])),_:1,__:[11]},8,["modelValue"])]))}},U={class:"demo-space-x"},E={__name:"DemoSnackbarVariants",setup(I){const o=d(!1),s=d(!1),t=d(!1),r=d(!1),c=d(!1);return(f,i)=>(B(),x("div",U,[a(k,{onClick:i[0]||(i[0]=p=>o.value=!0)},{default:e(()=>i[10]||(i[10]=[n(" Default ")])),_:1,__:[10]}),a(S,{modelValue:b(o),"onUpdate:modelValue":i[1]||(i[1]=p=>m(o)?o.value=p:null),location:"top start"},{default:e(()=>i[11]||(i[11]=[n(" Jelly chocolate bar candy canes apple pie. ")])),_:1,__:[11]},8,["modelValue"]),a(k,{onClick:i[2]||(i[2]=p=>s.value=!0)},{default:e(()=>i[12]||(i[12]=[n(" tonal ")])),_:1,__:[12]}),a(S,{modelValue:b(s),"onUpdate:modelValue":i[3]||(i[3]=p=>m(s)?s.value=p:null),location:"top end",variant:"tonal"},{default:e(()=>i[13]||(i[13]=[n(" Ice cream cake candy canes. ")])),_:1,__:[13]},8,["modelValue"]),a(k,{onClick:i[4]||(i[4]=p=>t.value=!0)},{default:e(()=>i[14]||(i[14]=[n(" Text ")])),_:1,__:[14]}),a(S,{modelValue:b(t),"onUpdate:modelValue":i[5]||(i[5]=p=>m(t)?t.value=p:null),location:"end center",variant:"text"},{default:e(()=>i[15]||(i[15]=[n(" Pie icing biscuit soufflé liquorice topping. ")])),_:1,__:[15]},8,["modelValue"]),a(k,{onClick:i[6]||(i[6]=p=>r.value=!0)},{default:e(()=>i[16]||(i[16]=[n(" Outlined ")])),_:1,__:[16]}),a(S,{modelValue:b(r),"onUpdate:modelValue":i[7]||(i[7]=p=>m(r)?r.value=p:null),location:"bottom end",variant:"outlined",color:"error"},{default:e(()=>i[17]||(i[17]=[n(" Oat cake caramels sesame snaps candy. ")])),_:1,__:[17]},8,["modelValue"]),a(k,{onClick:i[8]||(i[8]=p=>c.value=!0)},{default:e(()=>i[18]||(i[18]=[n(" Flat ")])),_:1,__:[18]}),a(S,{modelValue:b(c),"onUpdate:modelValue":i[9]||(i[9]=p=>m(c)?c.value=p:null),location:"bottom start",variant:"flat",color:"error"},{default:e(()=>i[19]||(i[19]=[n(" Oat cake caramels sesame snaps candy. ")])),_:1,__:[19]},8,["modelValue"])]))}},F={class:"demo-space-x"},R={__name:"DemoSnackbarPosition",setup(I){const o=d(!1),s=d(!1),t=d(!1),r=d(!1),c=d(!1),f=d(!1),i=d(!1),p=d(!1),g=d(!1);return(y,l)=>(B(),x("div",F,[a(k,{icon:"",variant:"text",onClick:l[0]||(l[0]=V=>s.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-up"})]),_:1}),a(S,{modelValue:b(s),"onUpdate:modelValue":l[1]||(l[1]=V=>m(s)?s.value=V:null),location:"top"},{default:e(()=>l[18]||(l[18]=[n(" I'm a top snackbar. ")])),_:1,__:[18]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[2]||(l[2]=V=>t.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-up-right"})]),_:1}),a(S,{modelValue:b(t),"onUpdate:modelValue":l[3]||(l[3]=V=>m(t)?t.value=V:null),location:"top end"},{default:e(()=>l[19]||(l[19]=[n(" I'm a top right snackbar. ")])),_:1,__:[19]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[4]||(l[4]=V=>i.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-right"})]),_:1}),a(S,{modelValue:b(i),"onUpdate:modelValue":l[5]||(l[5]=V=>m(i)?i.value=V:null),location:"end center"},{default:e(()=>l[20]||(l[20]=[n(" I'm a center end snackbar. ")])),_:1,__:[20]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[6]||(l[6]=V=>r.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-down-right"})]),_:1}),a(S,{modelValue:b(r),"onUpdate:modelValue":l[7]||(l[7]=V=>m(r)?r.value=V:null),location:"bottom end"},{default:e(()=>l[21]||(l[21]=[n(" I'm a bottom end snackbar. ")])),_:1,__:[21]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[8]||(l[8]=V=>c.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-down"})]),_:1}),a(S,{modelValue:b(c),"onUpdate:modelValue":l[9]||(l[9]=V=>m(c)?c.value=V:null)},{default:e(()=>l[22]||(l[22]=[n(" I'm a bottom snackbar. ")])),_:1,__:[22]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[10]||(l[10]=V=>f.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-down-left"})]),_:1}),a(S,{modelValue:b(f),"onUpdate:modelValue":l[11]||(l[11]=V=>m(f)?f.value=V:null),location:"bottom start"},{default:e(()=>l[23]||(l[23]=[n(" I'm a bottom start snackbar. ")])),_:1,__:[23]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[12]||(l[12]=V=>p.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-left"})]),_:1}),a(S,{modelValue:b(p),"onUpdate:modelValue":l[13]||(l[13]=V=>m(p)?p.value=V:null),location:"start center"},{default:e(()=>l[24]||(l[24]=[n(" I'm a center start snackbar. ")])),_:1,__:[24]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[14]||(l[14]=V=>o.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrow-up-left"})]),_:1}),a(S,{modelValue:b(o),"onUpdate:modelValue":l[15]||(l[15]=V=>m(o)?o.value=V:null),location:"top start"},{default:e(()=>l[25]||(l[25]=[n(" I'm a top start snackbar. ")])),_:1,__:[25]},8,["modelValue"]),a(k,{icon:"",variant:"text",onClick:l[16]||(l[16]=V=>g.value=!0)},{default:e(()=>[a(v,{icon:"tabler-arrows-minimize"})]),_:1}),a(S,{modelValue:b(g),"onUpdate:modelValue":l[17]||(l[17]=V=>m(g)?g.value=V:null),location:"center"},{default:e(()=>l[26]||(l[26]=[n(" I'm a center snackbar. ")])),_:1,__:[26]},8,["modelValue"])]))}},j={__name:"DemoSnackbarVertical",setup(I){const o=d(!1);return(s,t)=>(B(),x(T,null,[a(k,{onClick:t[0]||(t[0]=r=>o.value=!0)},{default:e(()=>t[4]||(t[4]=[n(" Open Snackbar ")])),_:1,__:[4]}),a(S,{modelValue:b(o),"onUpdate:modelValue":t[3]||(t[3]=r=>m(o)?o.value=r:null),vertical:""},{actions:e(()=>[a(k,{color:"success",onClick:t[1]||(t[1]=r=>o.value=!1)},{default:e(()=>t[5]||(t[5]=[n(" Undo ")])),_:1,__:[5]}),a(k,{color:"error",onClick:t[2]||(t[2]=r=>o.value=!1)},{default:e(()=>t[6]||(t[6]=[n(" Close ")])),_:1,__:[6]})]),default:e(()=>[t[7]||(t[7]=n(" Sugar plum chocolate bar halvah sesame snaps apple pie donut croissant marshmallow. Sweet roll donut gummies sesame snaps icing bear claw tiramisu cotton candy. "))]),_:1,__:[7]},8,["modelValue"])],64))}},q={__name:"DemoSnackbarTimeout",setup(I){const o=d(!1);return(s,t)=>(B(),x(T,null,[a(k,{onClick:t[0]||(t[0]=r=>o.value=!0)},{default:e(()=>t[2]||(t[2]=[n(" Open Snackbar ")])),_:1,__:[2]}),a(S,{modelValue:b(o),"onUpdate:modelValue":t[1]||(t[1]=r=>m(o)?o.value=r:null),timeout:2e3},{default:e(()=>t[3]||(t[3]=[n(" My timeout is set to 2000. ")])),_:1,__:[3]},8,["modelValue"])],64))}},A={__name:"DemoSnackbarMultiLine",setup(I){const o=d(!1);return(s,t)=>(B(),x(T,null,[a(k,{onClick:t[0]||(t[0]=r=>o.value=!0)},{default:e(()=>t[3]||(t[3]=[n(" Open Snackbar ")])),_:1,__:[3]}),a(S,{modelValue:b(o),"onUpdate:modelValue":t[2]||(t[2]=r=>m(o)?o.value=r:null),"multi-line":""},{actions:e(()=>[a(k,{color:"error",onClick:t[1]||(t[1]=r=>o.value=!1)},{default:e(()=>t[4]||(t[4]=[n(" Close ")])),_:1,__:[4]})]),default:e(()=>[t[5]||(t[5]=n(" I am a multi-line snackbar. I can have more than one line. This is another line that is quite long. "))]),_:1,__:[5]},8,["modelValue"])],64))}},H={__name:"DemoSnackbarWithAction",setup(I){const o=d(!1);return(s,t)=>(B(),x(T,null,[a(k,{onClick:t[0]||(t[0]=r=>o.value=!0)},{default:e(()=>t[3]||(t[3]=[n(" Open Snackbar ")])),_:1,__:[3]}),a(S,{modelValue:b(o),"onUpdate:modelValue":t[2]||(t[2]=r=>m(o)?o.value=r:null)},{actions:e(()=>[a(k,{color:"error",onClick:t[1]||(t[1]=r=>o.value=!1)},{default:e(()=>t[4]||(t[4]=[n(" Close ")])),_:1,__:[4]})]),default:e(()=>[t[5]||(t[5]=n(" Hello, I'm a snackbar with actions. "))]),_:1,__:[5]},8,["modelValue"])],64))}},M={__name:"DemoSnackbarBasic",setup(I){const o=d(!1);return(s,t)=>(B(),x(T,null,[a(k,{onClick:t[0]||(t[0]=r=>o.value=!0)},{default:e(()=>t[2]||(t[2]=[n(" Open Snackbar ")])),_:1,__:[2]}),a(S,{modelValue:b(o),"onUpdate:modelValue":t[1]||(t[1]=r=>m(o)?o.value=r:null)},{default:e(()=>t[3]||(t[3]=[n(" Hello, I'm a snackbar ")])),_:1,__:[3]},8,["modelValue"])],64))}},P={ts:`<script lang="ts" setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- SnackBar -->
  <VSnackbar v-model="isSnackbarVisible">
    Hello, I'm a snackbar
  </VSnackbar>
</template>
`,js:`<script setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- SnackBar -->
  <VSnackbar v-model="isSnackbarVisible">
    Hello, I'm a snackbar
  </VSnackbar>
</template>
`},z={ts:`<script lang="ts" setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar
    v-model="isSnackbarVisible"
    multi-line
  >
    I am a multi-line snackbar. I can have more than one line. This is another line that is quite long.

    <template #actions>
      <VBtn
        color="error"
        @click="isSnackbarVisible = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`,js:`<script setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar
    v-model="isSnackbarVisible"
    multi-line
  >
    I am a multi-line snackbar. I can have more than one line. This is another line that is quite long.

    <template #actions>
      <VBtn
        color="error"
        @click="isSnackbarVisible = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`},L={ts:`<script lang="ts" setup>
const isSnackbarTopStartVisible = ref(false)
const isSnackbarTopVisible = ref(false)
const isSnackbarTopEndVisible = ref(false)
const isSnackbarBottomEndVisible = ref(false)
const isSnackbarBottomVisible = ref(false)
const isSnackbarBottomStartVisible = ref(false)
const isSnackbarEndVisible = ref(false)
const isSnackbarStartVisible = ref(false)
const isSnackbarCenteredVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- top  -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopVisible = true"
    >
      <VIcon icon="tabler-arrow-up" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopVisible"
      location="top"
    >
      I'm a top snackbar.
    </VSnackbar>

    <!-- top end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopEndVisible = true"
    >
      <VIcon icon="tabler-arrow-up-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopEndVisible"
      location="top end"
    >
      I'm a top right snackbar.
    </VSnackbar>

    <!-- center end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarEndVisible = true"
    >
      <VIcon icon="tabler-arrow-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarEndVisible"
      location="end center"
    >
      I'm a center end snackbar.
    </VSnackbar>

    <!-- bottom end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomEndVisible = true"
    >
      <VIcon icon="tabler-arrow-down-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarBottomEndVisible"
      location="bottom end"
    >
      I'm a bottom end snackbar.
    </VSnackbar>

    <!-- bottom -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomVisible = true"
    >
      <VIcon icon="tabler-arrow-down" />
    </VBtn>

    <VSnackbar v-model="isSnackbarBottomVisible">
      I'm a bottom snackbar.
    </VSnackbar>

    <!-- bottom start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomStartVisible = true"
    >
      <VIcon icon="tabler-arrow-down-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarBottomStartVisible"
      location="bottom start"
    >
      I'm a bottom start snackbar.
    </VSnackbar>

    <!-- center start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarStartVisible = true"
    >
      <VIcon icon="tabler-arrow-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarStartVisible"
      location="start center"
    >
      I'm a center start snackbar.
    </VSnackbar>

    <!-- top start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopStartVisible = true"
    >
      <VIcon icon="tabler-arrow-up-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopStartVisible"
      location="top start"
    >
      I'm a top start snackbar.
    </VSnackbar>

    <!-- center -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarCenteredVisible = true"
    >
      <VIcon icon="tabler-arrows-minimize" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarCenteredVisible"
      location="center"
    >
      I'm a center snackbar.
    </VSnackbar>
  </div>
</template>
`,js:`<script setup>
const isSnackbarTopStartVisible = ref(false)
const isSnackbarTopVisible = ref(false)
const isSnackbarTopEndVisible = ref(false)
const isSnackbarBottomEndVisible = ref(false)
const isSnackbarBottomVisible = ref(false)
const isSnackbarBottomStartVisible = ref(false)
const isSnackbarEndVisible = ref(false)
const isSnackbarStartVisible = ref(false)
const isSnackbarCenteredVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- top  -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopVisible = true"
    >
      <VIcon icon="tabler-arrow-up" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopVisible"
      location="top"
    >
      I'm a top snackbar.
    </VSnackbar>

    <!-- top end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopEndVisible = true"
    >
      <VIcon icon="tabler-arrow-up-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopEndVisible"
      location="top end"
    >
      I'm a top right snackbar.
    </VSnackbar>

    <!-- center end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarEndVisible = true"
    >
      <VIcon icon="tabler-arrow-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarEndVisible"
      location="end center"
    >
      I'm a center end snackbar.
    </VSnackbar>

    <!-- bottom end -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomEndVisible = true"
    >
      <VIcon icon="tabler-arrow-down-right" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarBottomEndVisible"
      location="bottom end"
    >
      I'm a bottom end snackbar.
    </VSnackbar>

    <!-- bottom -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomVisible = true"
    >
      <VIcon icon="tabler-arrow-down" />
    </VBtn>

    <VSnackbar v-model="isSnackbarBottomVisible">
      I'm a bottom snackbar.
    </VSnackbar>

    <!-- bottom start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarBottomStartVisible = true"
    >
      <VIcon icon="tabler-arrow-down-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarBottomStartVisible"
      location="bottom start"
    >
      I'm a bottom start snackbar.
    </VSnackbar>

    <!-- center start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarStartVisible = true"
    >
      <VIcon icon="tabler-arrow-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarStartVisible"
      location="start center"
    >
      I'm a center start snackbar.
    </VSnackbar>

    <!-- top start -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarTopStartVisible = true"
    >
      <VIcon icon="tabler-arrow-up-left" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarTopStartVisible"
      location="top start"
    >
      I'm a top start snackbar.
    </VSnackbar>

    <!-- center -->
    <VBtn
      icon
      variant="text"
      @click="isSnackbarCenteredVisible = true"
    >
      <VIcon icon="tabler-arrows-minimize" />
    </VBtn>

    <VSnackbar
      v-model="isSnackbarCenteredVisible"
      location="center"
    >
      I'm a center snackbar.
    </VSnackbar>
  </div>
</template>
`},J={ts:`<script lang="ts" setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar
    v-model="isSnackbarVisible"
    :timeout="2000"
  >
    My timeout is set to 2000.
  </VSnackbar>
</template>
`,js:`<script setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar
    v-model="isSnackbarVisible"
    :timeout="2000"
  >
    My timeout is set to 2000.
  </VSnackbar>
</template>
`},N={ts:`<script lang="ts" setup>
const isSnackbarFadeVisible = ref(false)
const isSnackbarScaleVisible = ref(false)
const isSnackbarScrollReverseVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- fade -->
    <VBtn @click="isSnackbarFadeVisible = true">
      fade snackbar
    </VBtn>

    <VSnackbar
      v-model="isSnackbarFadeVisible"
      transition="fade-transition"
      location="top start"
    >
      I'm a fade transition snackbar.
    </VSnackbar>

    <!-- scale -->
    <VBtn @click="isSnackbarScaleVisible = true">
      Scale snackbar
    </VBtn>

    <VSnackbar
      v-model="isSnackbarScaleVisible"
      transition="scale-transition"
      location="bottom end"
    >
      I'm a scale transition snackbar.
    </VSnackbar>

    <!-- scroll y reverse -->
    <VBtn @click="isSnackbarScrollReverseVisible = true">
      scroll y reverse
    </VBtn>

    <VSnackbar
      v-model="isSnackbarScrollReverseVisible"
      transition="scroll-y-reverse-transition"
      location="top end"
    >
      I'm a scroll y reverse transition snackbar.
    </VSnackbar>
  </div>
</template>
`,js:`<script setup>
const isSnackbarFadeVisible = ref(false)
const isSnackbarScaleVisible = ref(false)
const isSnackbarScrollReverseVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- fade -->
    <VBtn @click="isSnackbarFadeVisible = true">
      fade snackbar
    </VBtn>

    <VSnackbar
      v-model="isSnackbarFadeVisible"
      transition="fade-transition"
      location="top start"
    >
      I'm a fade transition snackbar.
    </VSnackbar>

    <!-- scale -->
    <VBtn @click="isSnackbarScaleVisible = true">
      Scale snackbar
    </VBtn>

    <VSnackbar
      v-model="isSnackbarScaleVisible"
      transition="scale-transition"
      location="bottom end"
    >
      I'm a scale transition snackbar.
    </VSnackbar>

    <!-- scroll y reverse -->
    <VBtn @click="isSnackbarScrollReverseVisible = true">
      scroll y reverse
    </VBtn>

    <VSnackbar
      v-model="isSnackbarScrollReverseVisible"
      transition="scroll-y-reverse-transition"
      location="top end"
    >
      I'm a scroll y reverse transition snackbar.
    </VSnackbar>
  </div>
</template>
`},W={ts:`<script lang="ts" setup>
const isDefaultSnackbarVisible = ref(false)
const isTonalSnackbarVisible = ref(false)
const isTextSnackbarVisible = ref(false)
const isOutlinedSnackbarVisible = ref(false)
const isFlatSnackbarVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- Default toggle btn -->
    <VBtn @click="isDefaultSnackbarVisible = true">
      Default
    </VBtn>

    <!-- Default snackbar -->
    <VSnackbar
      v-model="isDefaultSnackbarVisible"
      location="top start"
    >
      Jelly chocolate bar candy canes apple pie.
    </VSnackbar>

    <!-- tonal toggle btn -->
    <VBtn @click="isTonalSnackbarVisible = true">
      tonal
    </VBtn>

    <!-- tonal snackbar -->
    <VSnackbar
      v-model="isTonalSnackbarVisible"
      location="top end"
      variant="tonal"
    >
      Ice cream cake candy canes.
    </VSnackbar>

    <!-- Text toggle btn -->
    <VBtn @click="isTextSnackbarVisible = true">
      Text
    </VBtn>

    <!-- Text snackbar -->
    <VSnackbar
      v-model="isTextSnackbarVisible"
      location="end center"
      variant="text"
    >
      Pie icing biscuit soufflé liquorice topping.
    </VSnackbar>

    <!-- Outline toggle btn -->
    <VBtn @click="isOutlinedSnackbarVisible = true">
      Outlined
    </VBtn>

    <!-- Outline snackbar -->
    <VSnackbar
      v-model="isOutlinedSnackbarVisible"
      location="bottom end"
      variant="outlined"
      color="error"
    >
      Oat cake caramels sesame snaps candy.
    </VSnackbar>

    <!-- flat toggle btn -->
    <VBtn @click="isFlatSnackbarVisible = true">
      Flat
    </VBtn>

    <!-- flat snackbar -->
    <VSnackbar
      v-model="isFlatSnackbarVisible"
      location="bottom start"
      variant="flat"
      color="error"
    >
      Oat cake caramels sesame snaps candy.
    </VSnackbar>
  </div>
</template>
`,js:`<script setup>
const isDefaultSnackbarVisible = ref(false)
const isTonalSnackbarVisible = ref(false)
const isTextSnackbarVisible = ref(false)
const isOutlinedSnackbarVisible = ref(false)
const isFlatSnackbarVisible = ref(false)
<\/script>

<template>
  <div class="demo-space-x">
    <!-- Default toggle btn -->
    <VBtn @click="isDefaultSnackbarVisible = true">
      Default
    </VBtn>

    <!-- Default snackbar -->
    <VSnackbar
      v-model="isDefaultSnackbarVisible"
      location="top start"
    >
      Jelly chocolate bar candy canes apple pie.
    </VSnackbar>

    <!-- tonal toggle btn -->
    <VBtn @click="isTonalSnackbarVisible = true">
      tonal
    </VBtn>

    <!-- tonal snackbar -->
    <VSnackbar
      v-model="isTonalSnackbarVisible"
      location="top end"
      variant="tonal"
    >
      Ice cream cake candy canes.
    </VSnackbar>

    <!-- Text toggle btn -->
    <VBtn @click="isTextSnackbarVisible = true">
      Text
    </VBtn>

    <!-- Text snackbar -->
    <VSnackbar
      v-model="isTextSnackbarVisible"
      location="end center"
      variant="text"
    >
      Pie icing biscuit soufflé liquorice topping.
    </VSnackbar>

    <!-- Outline toggle btn -->
    <VBtn @click="isOutlinedSnackbarVisible = true">
      Outlined
    </VBtn>

    <!-- Outline snackbar -->
    <VSnackbar
      v-model="isOutlinedSnackbarVisible"
      location="bottom end"
      variant="outlined"
      color="error"
    >
      Oat cake caramels sesame snaps candy.
    </VSnackbar>

    <!-- flat toggle btn -->
    <VBtn @click="isFlatSnackbarVisible = true">
      Flat
    </VBtn>

    <!-- flat snackbar -->
    <VSnackbar
      v-model="isFlatSnackbarVisible"
      location="bottom start"
      variant="flat"
      color="error"
    >
      Oat cake caramels sesame snaps candy.
    </VSnackbar>
  </div>
</template>
`},Z={ts:`<script lang="ts" setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <VSnackbar
    v-model="isSnackbarVisible"
    vertical
  >
    Sugar plum chocolate bar halvah sesame snaps apple pie donut croissant marshmallow. Sweet roll donut gummies sesame snaps icing bear claw tiramisu cotton candy.

    <template #actions>
      <VBtn
        color="success"
        @click="isSnackbarVisible = false"
      >
        Undo
      </VBtn>

      <VBtn
        color="error"
        @click="isSnackbarVisible = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`,js:`<script setup>
const isSnackbarVisible = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisible = true">
    Open Snackbar
  </VBtn>

  <VSnackbar
    v-model="isSnackbarVisible"
    vertical
  >
    Sugar plum chocolate bar halvah sesame snaps apple pie donut croissant marshmallow. Sweet roll donut gummies sesame snaps icing bear claw tiramisu cotton candy.

    <template #actions>
      <VBtn
        color="success"
        @click="isSnackbarVisible = false"
      >
        Undo
      </VBtn>

      <VBtn
        color="error"
        @click="isSnackbarVisible = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`},G={ts:`<script lang="ts" setup>
const isSnackbarVisibility = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisibility = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar v-model="isSnackbarVisibility">
    Hello, I'm a snackbar with actions.

    <template #actions>
      <VBtn
        color="error"
        @click="isSnackbarVisibility = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`,js:`<script setup>
const isSnackbarVisibility = ref(false)
<\/script>

<template>
  <VBtn @click="isSnackbarVisibility = true">
    Open Snackbar
  </VBtn>

  <!-- Snackbar -->
  <VSnackbar v-model="isSnackbarVisibility">
    Hello, I'm a snackbar with actions.

    <template #actions>
      <VBtn
        color="error"
        @click="isSnackbarVisibility = false"
      >
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
`},ka={__name:"snackbar",setup(I){return(o,s)=>{const t=M,r=C,c=H,f=A,i=q,p=j,g=R,y=E,l=D;return B(),w($,{class:"match-height"},{default:e(()=>[a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Basic",code:b(P)},{default:e(()=>[s[0]||(s[0]=u("p",null,[n("The "),u("code",null,"v-snackbar"),n(" component is used to display a quick message to a user. Snackbars support positioning, removal delay, and callbacks.")],-1)),a(t)]),_:1,__:[0]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"With Action",code:b(G)},{default:e(()=>[s[1]||(s[1]=u("p",null,[n("Use "),u("code",null,"actions"),n(" slot to add action button. A "),u("code",null,"v-snackbar"),n(" in its simplest form displays a temporary and closable notification to the user.")],-1)),a(c)]),_:1,__:[1]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Multi Line",code:b(z)},{default:e(()=>[s[2]||(s[2]=u("p",null,[n("The "),u("code",null,"multi-line"),n(" property extends the height of the "),u("code",null,"v-snackbar"),n(" to give you a little more room for content.")],-1)),a(f)]),_:1,__:[2]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Timeout",code:b(J)},{default:e(()=>[s[3]||(s[3]=u("p",null,[n("The "),u("code",null,"timeout"),n(" property lets you customize the delay before the "),u("code",null,"v-snackbar"),n(" is hidden.")],-1)),a(i)]),_:1,__:[3]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Vertical",code:b(Z)},{default:e(()=>[s[4]||(s[4]=u("p",null,[n("The "),u("code",null,"vertical"),n(" property allows you to stack the content of your "),u("code",null,"v-snackbar"),n(".")],-1)),a(p)]),_:1,__:[4]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Position",code:b(L)},{default:e(()=>[s[5]||(s[5]=u("p",null,[n("Use "),u("code",null,"location"),n(" prop to change the position of snackbar.")],-1)),a(g)]),_:1,__:[5]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Variants",code:b(W)},{default:e(()=>[s[6]||(s[6]=u("p",null,[n("Apply different styles to the snackbar using props such as "),u("code",null,"shaped"),n(", "),u("code",null,"rounded"),n(", "),u("code",null,"color"),n(", "),u("code",null,"text"),n(", "),u("code",null,"outlined"),n(", "),u("code",null,"tile"),n(" and more.")],-1)),a(y)]),_:1,__:[6]},8,["code"])]),_:1}),a(_,{cols:"12",md:"6"},{default:e(()=>[a(r,{title:"Transition",code:b(N)},{default:e(()=>[s[7]||(s[7]=u("p",null,"Use transition prop to sets the component transition.",-1)),a(l)]),_:1,__:[7]},8,["code"])]),_:1})]),_:1})}}};export{ka as default};
