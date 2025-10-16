import{_ as y}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{a as b,V as I}from"./VRow-Ckz7YbKq.js";import{V as C}from"./form-C-dsGi7Z.js";import{V as W}from"./VCheckbox-D80Izhik.js";import{f as i,k as h,o as a,e as n,c as _,F as R,h as $,p as f,b as c,v as w,d as p,y as v,s as V,t as g,r as k,$ as x,l as d,g as D,Z as j}from"./main-BZOEUSqC.js";import{V as U,a as B}from"./VRadioGroup-29Mvt0FY.js";import{_ as G}from"./CustomRadiosWithIcon-CFx2aS3Y.js";import{V as q}from"./VSpacer-wn1Vrj2i.js";import{_ as F}from"./AppCardCode-CFDu2213.js";import{_ as A}from"./CustomRadios-m5vwV304.js";/* empty css              */import"./VCheckboxBtn-DbEHaUEI.js";import"./VSelectionControl-Cmlvst-J.js";import"./VInput-CAiOqF1-.js";import"./VImg-D0MwLFWH.js";import"./VAvatar-CWaS20XD.js";import"./vue3-perfect-scrollbar-D3MhGK5S.js";import"./VCard-wYGHGAy0.js";import"./VCardText-tOLvzKvO.js";import"./VDivider-BxBXA6ri.js";const E=["src"],P={__name:"CustomCheckboxesWithImage",props:{selectedCheckbox:{type:Array,required:!0},checkboxContent:{type:Array,required:!0},gridColumn:{type:null,required:!1}},emits:["update:selectedCheckbox"],setup(r,{emit:l}){const e=r,m=l,s=o=>{typeof o!="boolean"&&o!==null&&m("update:selectedCheckbox",o)};return(o,u)=>e.checkboxContent&&e.selectedCheckbox?(a(),i(I,{key:0,class:"custom-input-wrapper"},{default:n(()=>[(a(!0),_(R,null,$(e.checkboxContent,t=>(a(),i(b,f({key:t.value,ref_for:!0},r.gridColumn),{default:n(()=>[c(C,{class:w(["custom-input custom-checkbox rounded cursor-pointer w-100",e.selectedCheckbox.includes(t.value)?"active":""])},{default:n(()=>[p("div",null,[c(W,{id:`custom-checkbox-with-img-${t.value}`,"model-value":e.selectedCheckbox,value:t.value,"onUpdate:modelValue":s},null,8,["id","model-value","value"])]),p("img",{src:t.bgImage,alt:"bg-img",class:"custom-checkbox-image"},null,8,E)]),_:2},1032,["class"]),t.label||o.$slots.label?(a(),i(C,{key:0,for:`custom-checkbox-with-img-${t.value}`,class:"cursor-pointer"},{default:n(()=>[v(o.$slots,"label",{label:t.label},()=>[V(g(t.label),1)],!0)]),_:2},1032,["for"])):h("",!0)]),_:2},1040))),128))]),_:3})):h("",!0)}},N=y(P,[["__scopeId","data-v-33ec7233"]]),T="/build/assets/custom-checkbox-img-1-CN62rwL8.png",L="/build/assets/custom-checkbox-img-2-CESZ7JlS.png",J="/build/assets/custom-checkbox-img-3-CFGEoD3K.png",O={__name:"DemoCustomInputCustomCheckboxesWithImage",setup(r){const l=[{bgImage:T,value:"basic"},{bgImage:L,value:"premium"},{bgImage:J,value:"enterprise"}],e=k(["basic"]);return(m,s)=>{const o=N;return a(),i(o,{"selected-checkbox":d(e),"onUpdate:selectedCheckbox":s[0]||(s[0]=u=>x(e)?e.value=u:null),"checkbox-content":l,"grid-column":{sm:"4",cols:"12"}},null,8,["selected-checkbox"])}}},K=["src"],Y={__name:"CustomRadiosWithImage",props:{selectedRadio:{type:String,required:!0},radioContent:{type:Array,required:!0},gridColumn:{type:null,required:!1}},emits:["update:selectedRadio"],setup(r,{emit:l}){const e=r,m=l,s=o=>{o!==null&&m("update:selectedRadio",o)};return(o,u)=>e.radioContent?(a(),i(B,{key:0,"model-value":e.selectedRadio,class:"custom-input-wrapper","onUpdate:modelValue":s},{default:n(()=>[c(I,null,{default:n(()=>[(a(!0),_(R,null,$(e.radioContent,t=>(a(),i(b,f({key:t.bgImage,ref_for:!0},r.gridColumn),{default:n(()=>[c(C,{class:w(["custom-input custom-radio rounded cursor-pointer w-100",e.selectedRadio===t.value?"active":""])},{default:n(()=>[v(o.$slots,"content",{item:t},()=>[typeof t.bgImage=="object"?(a(),i(D(t.bgImage),{key:0,class:"custom-radio-image"})):(a(),_("img",{key:1,src:t.bgImage,alt:"bg-img",class:"custom-radio-image"},null,8,K))],!0),c(U,{id:`custom-radio-with-img-${t.value}`,name:`custom-radio-with-img-${t.value}`,value:t.value},null,8,["id","name","value"])]),_:2},1032,["class"]),t.label||o.$slots.label?(a(),i(C,{key:0,for:`custom-radio-with-img-${t.value}`,class:"cursor-pointer"},{default:n(()=>[v(o.$slots,"label",{label:t.label},()=>[V(g(t.label),1)],!0)]),_:2},1032,["for"])):h("",!0)]),_:2},1040))),128))]),_:3})]),_:3},8,["model-value"])):h("",!0)}},Z=y(Y,[["__scopeId","data-v-8e53cdec"]]),H="/build/assets/custom-radio-img-1-YrBKH0Sm.png",X="/build/assets/custom-radio-img-2-Ph1YXgv1.png",M="/build/assets/custom-radio-img-3-Bg8gANbN.png",Q={__name:"DemoCustomInputCustomRadiosWithImage",setup(r){const l=[{bgImage:H,value:"basic"},{bgImage:X,value:"premium"},{bgImage:M,value:"enterprise"}],e=k("basic");return(m,s)=>{const o=Z;return a(),i(o,{"selected-radio":d(e),"onUpdate:selectedRadio":s[0]||(s[0]=u=>x(e)?e.value=u:null),"radio-content":l,"grid-column":{sm:"4",cols:"12"}},null,8,["selected-radio"])}}},ee={class:"d-flex flex-column align-center text-center gap-2"},te={class:"cr-title text-base"},oe={class:"text-sm clamp-text mb-0"},se={__name:"CustomCheckboxesWithIcon",props:{selectedCheckbox:{type:Array,required:!0},checkboxContent:{type:Array,required:!0},gridColumn:{type:null,required:!1}},emits:["update:selectedCheckbox"],setup(r,{emit:l}){const e=r,m=l,s=o=>{typeof o!="boolean"&&o!==null&&m("update:selectedCheckbox",o)};return(o,u)=>e.checkboxContent&&e.selectedCheckbox?(a(),i(I,{key:0,class:"custom-input-wrapper"},{default:n(()=>[(a(!0),_(R,null,$(e.checkboxContent,t=>(a(),i(b,f({key:t.title,ref_for:!0},r.gridColumn),{default:n(()=>[c(C,{class:w(["custom-input custom-checkbox-icon rounded cursor-pointer",e.selectedCheckbox.includes(t.value)?"active":""])},{default:n(()=>[v(o.$slots,"default",{item:t},()=>[p("div",ee,[c(j,f({ref_for:!0},t.icon,{class:"text-high-emphasis"}),null,16),p("h6",te,g(t.title),1),p("p",oe,g(t.desc),1)])],!0),p("div",null,[c(W,{"model-value":e.selectedCheckbox,value:t.value,"onUpdate:modelValue":s},null,8,["model-value","value"])])]),_:2},1032,["class"])]),_:2},1040))),128))]),_:3})):h("",!0)}},ce=y(se,[["__scopeId","data-v-fb25eaf8"]]),ne={__name:"DemoCustomInputCustomCheckboxesWithIcon",setup(r){const l=[{title:"Backup",desc:"Backup every file from your project.",value:"backup",icon:{icon:"tabler-server-2",size:"28"}},{title:"Encrypt",desc:"Translate your data to encrypted text.",value:"encrypt",icon:{icon:"tabler-ban",size:"28"}},{title:"Site Lock",desc:"Security tool to protect your website.",value:"site-lock",icon:{icon:"tabler-lock",size:"28"}}],e=k(["backup"]);return(m,s)=>{const o=ce;return a(),i(o,{"selected-checkbox":d(e),"onUpdate:selectedCheckbox":s[0]||(s[0]=u=>x(e)?e.value=u:null),"checkbox-content":l,"grid-column":{sm:"4",cols:"12"}},null,8,["selected-checkbox"])}}},ae={__name:"DemoCustomInputCustomRadiosWithIcon",setup(r){const l=[{title:"Starter",desc:"For freelancers who work with multiple clients",value:"starter",icon:{icon:"tabler-rocket",size:"28"}},{title:"Personal",desc:"Join our talented community of talented digital agencies",value:"personal",icon:{icon:"tabler-star",size:"28"}},{title:"Enterprise",desc:"Team plan for free upto 15 seats",value:"enterprise",icon:{icon:"tabler-crown",size:"28"}}],e=k("starter");return(m,s)=>{const o=G;return a(),i(o,{"selected-radio":d(e),"onUpdate:selectedRadio":s[0]||(s[0]=u=>x(e)?e.value=u:null),"radio-content":l,"grid-column":{sm:"4",cols:"12"}},null,8,["selected-radio"])}}},le={class:"flex-grow-1"},re={class:"d-flex align-center mb-2"},ie={class:"cr-title text-base"},ue={key:0,class:"text-disabled text-body-2"},me={class:"text-sm mb-0"},de={__name:"CustomCheckboxes",props:{selectedCheckbox:{type:Array,required:!0},checkboxContent:{type:Array,required:!0},gridColumn:{type:null,required:!1}},emits:["update:selectedCheckbox"],setup(r,{emit:l}){const e=r,m=l,s=o=>{typeof o!="boolean"&&o!==null&&m("update:selectedCheckbox",o)};return(o,u)=>e.checkboxContent&&e.selectedCheckbox?(a(),i(I,{key:0,class:"custom-input-wrapper"},{default:n(()=>[(a(!0),_(R,null,$(e.checkboxContent,t=>(a(),i(b,f({key:t.title,ref_for:!0},r.gridColumn),{default:n(()=>[c(C,{class:w(["custom-input custom-checkbox rounded cursor-pointer",e.selectedCheckbox.includes(t.value)?"active":""])},{default:n(()=>[p("div",null,[c(W,{"model-value":e.selectedCheckbox,value:t.value,"onUpdate:modelValue":s},null,8,["model-value","value"])]),v(o.$slots,"default",{item:t},()=>[p("div",le,[p("div",re,[p("h6",ie,g(t.title),1),c(q),t.subtitle?(a(),_("span",ue,g(t.subtitle),1)):h("",!0)]),p("p",me,g(t.desc),1)])],!0)]),_:2},1032,["class"])]),_:2},1040))),128))]),_:3})):h("",!0)}},pe=y(de,[["__scopeId","data-v-cb9d7fbe"]]),be={__name:"DemoCustomInputCustomCheckboxes",setup(r){const l=[{title:"Discount",subtitle:"20%",desc:"Wow! Get 20% off on your next purchase!",value:"discount"},{title:"Updates",subtitle:"Free",desc:"Get Updates regarding related products.",value:"updates"}],e=k(["discount"]);return(m,s)=>{const o=pe;return a(),i(o,{"selected-checkbox":d(e),"onUpdate:selectedCheckbox":s[0]||(s[0]=u=>x(e)?e.value=u:null),"checkbox-content":l,"grid-column":{sm:"6",cols:"12"}},null,8,["selected-checkbox"])}}},ge={__name:"DemoCustomInputCustomRadios",setup(r){const l=[{title:"Basic",subtitle:"Free",desc:"Get 1 project with 1 team member.",value:"basic"},{title:"Premium",subtitle:"$45.80",value:"premium",desc:"Get 5 projects with 5 team members."}],e=k("basic");return(m,s)=>{const o=A;return a(),i(o,{"selected-radio":d(e),"onUpdate:selectedRadio":s[0]||(s[0]=u=>x(e)?e.value=u:null),"radio-content":l,"grid-column":{sm:"6",cols:"12"}},null,8,["selected-radio"])}}},he={ts:`<script setup lang="ts">
import type { CustomInputContent } from '@core/types'

const checkboxContent: CustomInputContent[] = [
  {
    title: 'Discount',
    subtitle: '20%',
    desc: 'Wow! Get 20% off on your next purchase!',
    value: 'discount',
  },
  {
    title: 'Updates',
    subtitle: 'Free',
    desc: 'Get Updates regarding related products.',
    value: 'updates',
  },
]

const selectedCheckbox = ref(['discount'])
<\/script>

<template>
  <CustomCheckboxes
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '6', cols: '12' }"
  />
</template>
`,js:`<script setup>
const checkboxContent = [
  {
    title: 'Discount',
    subtitle: '20%',
    desc: 'Wow! Get 20% off on your next purchase!',
    value: 'discount',
  },
  {
    title: 'Updates',
    subtitle: 'Free',
    desc: 'Get Updates regarding related products.',
    value: 'updates',
  },
]

const selectedCheckbox = ref(['discount'])
<\/script>

<template>
  <CustomCheckboxes
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '6', cols: '12' }"
  />
</template>
`},Ce={ts:`<script setup lang="ts">
import type { CustomInputContent } from '@core/types'

const checkboxContent: CustomInputContent[] = [
  {
    title: 'Backup',
    desc: 'Backup every file from your project.',
    value: 'backup',
    icon: { icon: 'tabler-server-2', size: '28' },
  },
  {
    title: 'Encrypt',
    desc: 'Translate your data to encrypted text.',
    value: 'encrypt',
    icon: { icon: 'tabler-ban', size: '28' },
  },
  {
    title: 'Site Lock',
    desc: 'Security tool to protect your website.',
    value: 'site-lock',
    icon: { icon: 'tabler-lock', size: '28' },
  },
]

const selectedCheckbox = ref(['backup'])
<\/script>

<template>
  <CustomCheckboxesWithIcon
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`,js:`<script setup>
const checkboxContent = [
  {
    title: 'Backup',
    desc: 'Backup every file from your project.',
    value: 'backup',
    icon: {
      icon: 'tabler-server-2',
      size: '28',
    },
  },
  {
    title: 'Encrypt',
    desc: 'Translate your data to encrypted text.',
    value: 'encrypt',
    icon: {
      icon: 'tabler-ban',
      size: '28',
    },
  },
  {
    title: 'Site Lock',
    desc: 'Security tool to protect your website.',
    value: 'site-lock',
    icon: {
      icon: 'tabler-lock',
      size: '28',
    },
  },
]

const selectedCheckbox = ref(['backup'])
<\/script>

<template>
  <CustomCheckboxesWithIcon
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`},_e={ts:`<script setup lang="ts">
import bg1 from '@images/pages/custom-checkbox-img-1.png'
import bg2 from '@images/pages/custom-checkbox-img-2.png'
import bg3 from '@images/pages/custom-checkbox-img-3.png'

const checkboxContent: { bgImage: string; value: string }[] = [
  {
    bgImage: bg1,
    value: 'basic',
  },
  {
    bgImage: bg2,
    value: 'premium',
  },
  {
    bgImage: bg3,
    value: 'enterprise',
  },
]

const selectedCheckbox = ref(['basic'])
<\/script>

<template>
  <CustomCheckboxesWithImage
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`,js:`<script setup>
import bg1 from '@images/pages/custom-checkbox-img-1.png'
import bg2 from '@images/pages/custom-checkbox-img-2.png'
import bg3 from '@images/pages/custom-checkbox-img-3.png'

const checkboxContent = [
  {
    bgImage: bg1,
    value: 'basic',
  },
  {
    bgImage: bg2,
    value: 'premium',
  },
  {
    bgImage: bg3,
    value: 'enterprise',
  },
]

const selectedCheckbox = ref(['basic'])
<\/script>

<template>
  <CustomCheckboxesWithImage
    v-model:selected-checkbox="selectedCheckbox"
    :checkbox-content="checkboxContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`},ke={ts:`<script setup lang="ts">
import type { CustomInputContent } from '@core/types'

const radioContent: CustomInputContent[] = [
  {
    title: 'Basic',
    subtitle: 'Free',
    desc: 'Get 1 project with 1 team member.',
    value: 'basic',
  },
  {
    title: 'Premium',
    subtitle: '$45.80',
    value: 'premium',
    desc: 'Get 5 projects with 5 team members.',
  },
]

const selectedRadio = ref('basic')
<\/script>

<template>
  <CustomRadios
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '6', cols: '12' }"
  />
</template>
`,js:`<script setup>
const radioContent = [
  {
    title: 'Basic',
    subtitle: 'Free',
    desc: 'Get 1 project with 1 team member.',
    value: 'basic',
  },
  {
    title: 'Premium',
    subtitle: '$45.80',
    value: 'premium',
    desc: 'Get 5 projects with 5 team members.',
  },
]

const selectedRadio = ref('basic')
<\/script>

<template>
  <CustomRadios
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '6', cols: '12' }"
  />
</template>
`},xe={ts:`<script setup lang="ts">
import type { CustomInputContent } from '@core/types'

const radioContent: CustomInputContent[] = [
  {
    title: 'Starter',
    desc: 'For freelancers who work with multiple clients',
    value: 'starter',
    icon: { icon: 'tabler-rocket', size: '28' },
  },
  {
    title: 'Personal',
    desc: 'Join our talented community of talented digital agencies',
    value: 'personal',
    icon: { icon: 'tabler-star', size: '28' },
  },
  {
    title: 'Enterprise',
    desc: 'Team plan for free upto 15 seats',
    value: 'enterprise',
    icon: { icon: 'tabler-crown', size: '28' },
  },
]

const selectedRadio = ref('starter')
<\/script>

<template>
  <CustomRadiosWithIcon
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`,js:`<script setup>
const radioContent = [
  {
    title: 'Starter',
    desc: 'For freelancers who work with multiple clients',
    value: 'starter',
    icon: {
      icon: 'tabler-rocket',
      size: '28',
    },
  },
  {
    title: 'Personal',
    desc: 'Join our talented community of talented digital agencies',
    value: 'personal',
    icon: {
      icon: 'tabler-star',
      size: '28',
    },
  },
  {
    title: 'Enterprise',
    desc: 'Team plan for free upto 15 seats',
    value: 'enterprise',
    icon: {
      icon: 'tabler-crown',
      size: '28',
    },
  },
]

const selectedRadio = ref('starter')
<\/script>

<template>
  <CustomRadiosWithIcon
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`},fe={ts:`<script setup lang="ts">
import bg1 from '@images/pages/custom-radio-img-1.png'
import bg2 from '@images/pages/custom-radio-img-2.png'
import bg3 from '@images/pages/custom-radio-img-3.png'

const radioContent: { bgImage: string; value: string }[] = [
  {
    bgImage: bg1,
    value: 'basic',
  },
  {
    bgImage: bg2,
    value: 'premium',
  },
  {
    bgImage: bg3,
    value: 'enterprise',
  },
]

const selectedRadio = ref('basic')
<\/script>

<template>
  <CustomRadiosWithImage
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`,js:`<script setup>
import bg1 from '@images/pages/custom-radio-img-1.png'
import bg2 from '@images/pages/custom-radio-img-2.png'
import bg3 from '@images/pages/custom-radio-img-3.png'

const radioContent = [
  {
    bgImage: bg1,
    value: 'basic',
  },
  {
    bgImage: bg2,
    value: 'premium',
  },
  {
    bgImage: bg3,
    value: 'enterprise',
  },
]

const selectedRadio = ref('basic')
<\/script>

<template>
  <CustomRadiosWithImage
    v-model:selected-radio="selectedRadio"
    :radio-content="radioContent"
    :grid-column="{ sm: '4', cols: '12' }"
  />
</template>
`},Ne={__name:"custom-input",setup(r){return(l,e)=>{const m=ge,s=F,o=be,u=ae,t=ne,S=Q,z=O;return a(),i(I,null,{default:n(()=>[c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Radios",code:d(ke)},{default:n(()=>[c(m)]),_:1},8,["code"])]),_:1}),c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Checkboxes",code:d(he)},{default:n(()=>[c(o)]),_:1},8,["code"])]),_:1}),c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Radios With Icon",code:d(xe)},{default:n(()=>[c(u)]),_:1},8,["code"])]),_:1}),c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Checkboxes With Icon",code:d(Ce)},{default:n(()=>[c(t)]),_:1},8,["code"])]),_:1}),c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Radios With Image",code:d(fe)},{default:n(()=>[c(S)]),_:1},8,["code"])]),_:1}),c(b,{cols:"12",md:"6"},{default:n(()=>[c(s,{title:"Custom Checkboxes With Image",code:d(_e)},{default:n(()=>[c(z)]),_:1},8,["code"])]),_:1})]),_:1})}}};export{Ne as default};
