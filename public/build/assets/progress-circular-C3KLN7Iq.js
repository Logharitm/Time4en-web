import{_ as g}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{c as p,o as n,b as r,cB as o,r as _,E as f,aB as y,e as l,s,t as d,l as t,f as h,d as a}from"./main-riJjh_1G.js";import{_ as z}from"./AppCardCode-B66GdEUU.js";import{V as w,a as m}from"./VRow-7hJ9GT2Z.js";import"./vue3-perfect-scrollbar-DrpjXL6b.js";import"./VCard-Ww1Gv9Tx.js";import"./VAvatar-1VQU8uqf.js";import"./VImg-VG8KoBZL.js";import"./VCardText-DboL7g11.js";import"./VDivider-2n3v39aK.js";/* empty css              */const x={},$={class:"demo-space-x"};function B(u,i){return n(),p("div",$,[r(o,{size:30,width:"3",color:"primary",indeterminate:""}),r(o,{size:40,color:"primary",indeterminate:""}),r(o,{size:50,color:"primary",indeterminate:""}),r(o,{size:60,color:"primary",indeterminate:""})])}const I=g(x,[["render",B]]),D={class:"demo-space-x"},j={__name:"DemoProgressCircularRotate",setup(u){const i=_(),e=_(0);return f(()=>{i.value=setInterval(()=>{if(e.value===100)return e.value=0;e.value+=10},1e3)}),y(()=>{clearInterval(i.value)}),(v,c)=>(n(),p("div",D,[r(o,{rotate:360,size:70,width:6,"model-value":t(e),color:"primary"},{default:l(()=>[s(d(t(e)),1)]),_:1},8,["model-value"]),r(o,{rotate:90,size:70,width:6,"model-value":t(e),color:"primary"},{default:l(()=>[s(d(t(e)),1)]),_:1},8,["model-value"]),r(o,{rotate:170,size:70,width:6,"model-value":t(e),color:"primary"},{default:l(()=>[s(d(t(e)),1)]),_:1},8,["model-value"]),r(o,{rotate:-90,size:70,width:6,"model-value":t(e),color:"primary"},{default:l(()=>[s(d(t(e)),1)]),_:1},8,["model-value"])]))}},R={},U={class:"demo-space-x"};function b(u,i){return n(),p("div",U,[r(o,{indeterminate:"",color:"primary"}),r(o,{indeterminate:"",color:"secondary"}),r(o,{indeterminate:"",color:"success"}),r(o,{indeterminate:"",color:"info"}),r(o,{indeterminate:"",color:"warning"}),r(o,{indeterminate:"",color:"error"})])}const k=g(R,[["render",b]]),M={},N={class:"demo-space-x"};function S(u,i){return n(),p("div",N,[r(o,{"model-value":"50",color:"primary"}),r(o,{"model-value":"50",color:"secondary"}),r(o,{"model-value":"50",color:"success"}),r(o,{"model-value":"50",color:"info"}),r(o,{"model-value":"50",color:"warning"}),r(o,{"model-value":"50",color:"error"})])}const T=g(M,[["render",S]]),A={ts:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      model-value="50"
      color="primary"
    />

    <VProgressCircular
      model-value="50"
      color="secondary"
    />

    <VProgressCircular
      model-value="50"
      color="success"
    />

    <VProgressCircular
      model-value="50"
      color="info"
    />

    <VProgressCircular
      model-value="50"
      color="warning"
    />

    <VProgressCircular
      model-value="50"
      color="error"
    />
  </div>
</template>
`,js:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      model-value="50"
      color="primary"
    />

    <VProgressCircular
      model-value="50"
      color="secondary"
    />

    <VProgressCircular
      model-value="50"
      color="success"
    />

    <VProgressCircular
      model-value="50"
      color="info"
    />

    <VProgressCircular
      model-value="50"
      color="warning"
    />

    <VProgressCircular
      model-value="50"
      color="error"
    />
  </div>
</template>
`},E={ts:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      indeterminate
      color="primary"
    />

    <VProgressCircular
      indeterminate
      color="secondary"
    />

    <VProgressCircular
      indeterminate
      color="success"
    />

    <VProgressCircular
      indeterminate
      color="info"
    />

    <VProgressCircular
      indeterminate
      color="warning"
    />

    <VProgressCircular
      indeterminate
      color="error"
    />
  </div>
</template>
`,js:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      indeterminate
      color="primary"
    />

    <VProgressCircular
      indeterminate
      color="secondary"
    />

    <VProgressCircular
      indeterminate
      color="success"
    />

    <VProgressCircular
      indeterminate
      color="info"
    />

    <VProgressCircular
      indeterminate
      color="warning"
    />

    <VProgressCircular
      indeterminate
      color="error"
    />
  </div>
</template>
`},q={ts:`<script setup lang="ts">
const interval = ref()
const progressValue = ref(0)

onMounted(() => {
  interval.value = setInterval(() => {
    if (progressValue.value === 100)
      return progressValue.value = 0
    progressValue.value += 10
  }, 1000)
})

onBeforeUnmount(() => {
  clearInterval(interval.value)
})
<\/script>

<template>
  <div class="demo-space-x">
    <VProgressCircular
      :rotate="360"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="90"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="170"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="-90"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>
  </div>
</template>
`,js:`<script setup>
const interval = ref()
const progressValue = ref(0)

onMounted(() => {
  interval.value = setInterval(() => {
    if (progressValue.value === 100)
      return progressValue.value = 0
    progressValue.value += 10
  }, 1000)
})
onBeforeUnmount(() => {
  clearInterval(interval.value)
})
<\/script>

<template>
  <div class="demo-space-x">
    <VProgressCircular
      :rotate="360"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="90"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="170"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>

    <VProgressCircular
      :rotate="-90"
      :size="70"
      :width="6"
      :model-value="progressValue"
      color="primary"
    >
      {{ progressValue }}
    </VProgressCircular>
  </div>
</template>
`},F={ts:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      :size="30"
      width="3"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="40"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="50"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="60"
      color="primary"
      indeterminate
    />
  </div>
</template>
`,js:`<template>
  <div class="demo-space-x">
    <VProgressCircular
      :size="30"
      width="3"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="40"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="50"
      color="primary"
      indeterminate
    />

    <VProgressCircular
      :size="60"
      color="primary"
      indeterminate
    />
  </div>
</template>
`},rr={__name:"progress-circular",setup(u){return(i,e)=>{const v=T,c=z,V=k,C=j,P=I;return n(),h(w,{class:"match-height"},{default:l(()=>[r(m,{cols:"12",md:"6"},{default:l(()=>[r(c,{title:"color",code:t(A)},{default:l(()=>[e[0]||(e[0]=a("p",null,[s("Alternate colors can be applied to "),a("code",null,"v-progress-circular"),s(" using the "),a("code",null,"color"),s(" prop.")],-1)),r(v)]),_:1,__:[0]},8,["code"])]),_:1}),r(m,{cols:"12",md:"6"},{default:l(()=>[r(c,{title:"Indeterminate",code:t(E)},{default:l(()=>[e[1]||(e[1]=a("p",null,[s("Using the "),a("code",null,"indeterminate"),s(" prop, a "),a("code",null,"v-progress-circular"),s(" continues to animate indefinitely.")],-1)),r(V)]),_:1,__:[1]},8,["code"])]),_:1}),r(m,{cols:"12",md:"6"},{default:l(()=>[r(c,{title:"Rotate",code:t(q)},{default:l(()=>[e[2]||(e[2]=a("p",null,[s("The "),a("code",null,"rotate"),s(" prop gives you the ability to customize the "),a("code",null,"v-progress-circular"),s("'s origin.")],-1)),r(C)]),_:1,__:[2]},8,["code"])]),_:1}),r(m,{cols:"12",md:"6"},{default:l(()=>[r(c,{title:"Size",code:t(F)},{default:l(()=>[e[3]||(e[3]=a("p",null,[s("The "),a("code",null,"size"),s(" and "),a("code",null,"width"),s(" props allow you to easily alter the size and width of the "),a("code",null,"v-progress-circular"),s(" component.")],-1)),r(P)]),_:1,__:[3]},8,["code"])]),_:1})]),_:1})}}};export{rr as default};
