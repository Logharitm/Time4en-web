import{u as X,a as ee,m as le,g as Y,V as ae,b as E}from"./VSliderTrack-Dbdexx-y.js";import{V as K,m as te}from"./VInput-CKL19ZnS.js";import{u as se,m as ue,V as oe}from"./form-DUZPa8df.js";import{ac as ne,ad as re,r as g,b1 as ie,b0 as de,V as Z,aK as ce,b as t,p as me,F as pe,f as h,o as R,$,l as f,e as c,Z as ve,d,s as v}from"./main-Buooeazs.js";import{_ as fe}from"./AppCardCode-D4Y95NZI.js";import{V as be,a as k}from"./VRow-BcoryuSp.js";import"./VImg--AoPooaC.js";import"./vue3-perfect-scrollbar-DqIZH1Uk.js";import"./VCard-BTbWDGHM.js";import"./VAvatar-CkASEzQQ.js";import"./VCardText-oSvKZv6T.js";import"./VDivider-DRV0Ilys.js";/* empty css              */const Ve=re({...ue(),...te(),...le(),strict:Boolean,modelValue:{type:Array,default:()=>[0,0]}},"VRangeSlider"),x=ne()({name:"VRangeSlider",props:Ve(),emits:{"update:focused":e=>!0,"update:modelValue":e=>!0,end:e=>!0,start:e=>!0},setup(e,u){let{slots:l,emit:o}=u;const a=g(),i=g(),S=g(),{rtlClasses:j}=ie();function I(m){if(!a.value||!i.value)return;const p=Y(m,a.value.$el,e.direction),r=Y(m,i.value.$el,e.direction),n=Math.abs(p),b=Math.abs(r);return n<b||n===b&&p<0?a.value.$el:i.value.$el}const U=X(e),s=de(e,"modelValue",void 0,m=>m!=null&&m.length?m.map(p=>U.roundValue(p)):[0,0]),{activeThumbRef:V,hasLabels:q,max:B,min:y,mousePressed:G,onSliderMousedown:H,onSliderTouchstart:J,position:z,trackContainerRef:Q,readonly:L}=ee({props:e,steps:U,onSliderStart:()=>{o("start",s.value)},onSliderEnd:m=>{var n;let{value:p}=m;const r=V.value===((n=a.value)==null?void 0:n.$el)?[p,s.value[1]]:[s.value[0],p];!e.strict&&r[0]<r[1]&&(s.value=r),o("end",s.value)},onSliderMove:m=>{var b,w,D,_;let{value:p}=m;const[r,n]=s.value;!e.strict&&r===n&&r!==y.value&&(V.value=p>r?(b=i.value)==null?void 0:b.$el:(w=a.value)==null?void 0:w.$el,(D=V.value)==null||D.focus()),V.value===((_=a.value)==null?void 0:_.$el)?s.value=[Math.min(p,n),n]:s.value=[r,Math.max(r,p)]},getActiveThumb:I}),{isFocused:M,focus:A,blur:N}=se(e),O=Z(()=>z(s.value[0])),W=Z(()=>z(s.value[1]));return ce(()=>{const m=K.filterProps(e),p=!!(e.label||l.label||l.prepend);return t(K,me({class:["v-slider","v-range-slider",{"v-slider--has-labels":!!l["tick-label"]||q.value,"v-slider--focused":M.value,"v-slider--pressed":G.value,"v-slider--disabled":e.disabled},j.value,e.class],style:e.style,ref:S},m,{focused:M.value}),{...l,prepend:p?r=>{var n,b;return t(pe,null,[((n=l.label)==null?void 0:n.call(l,r))??(e.label?t(oe,{class:"v-slider__label",text:e.label},null):void 0),(b=l.prepend)==null?void 0:b.call(l,r)])}:void 0,default:r=>{var w,D;let{id:n,messagesId:b}=r;return t("div",{class:"v-slider__container",onMousedown:L.value?void 0:H,onTouchstartPassive:L.value?void 0:J},[t("input",{id:`${n.value}_start`,name:e.name||n.value,disabled:!!e.disabled,readonly:!!e.readonly,tabindex:"-1",value:s.value[0]},null),t("input",{id:`${n.value}_stop`,name:e.name||n.value,disabled:!!e.disabled,readonly:!!e.readonly,tabindex:"-1",value:s.value[1]},null),t(ae,{ref:Q,start:O.value,stop:W.value},{"tick-label":l["tick-label"]}),t(E,{ref:a,"aria-describedby":b.value,focused:M&&V.value===((w=a.value)==null?void 0:w.$el),modelValue:s.value[0],"onUpdate:modelValue":_=>s.value=[_,s.value[1]],onFocus:_=>{var T,F,C,P;A(),V.value=(T=a.value)==null?void 0:T.$el,B.value!==y.value&&s.value[0]===s.value[1]&&s.value[1]===y.value&&_.relatedTarget!==((F=i.value)==null?void 0:F.$el)&&((C=a.value)==null||C.$el.blur(),(P=i.value)==null||P.$el.focus())},onBlur:()=>{N(),V.value=void 0},min:y.value,max:s.value[1],position:O.value,ripple:e.ripple},{"thumb-label":l["thumb-label"]}),t(E,{ref:i,"aria-describedby":b.value,focused:M&&V.value===((D=i.value)==null?void 0:D.$el),modelValue:s.value[1],"onUpdate:modelValue":_=>s.value=[s.value[0],_],onFocus:_=>{var T,F,C,P;A(),V.value=(T=i.value)==null?void 0:T.$el,B.value!==y.value&&s.value[0]===s.value[1]&&s.value[0]===B.value&&_.relatedTarget!==((F=a.value)==null?void 0:F.$el)&&((C=i.value)==null||C.$el.blur(),(P=a.value)==null||P.$el.focus())},onBlur:()=>{N(),V.value=void 0},min:s.value[0],max:B.value,position:W.value,ripple:e.ripple},{"thumb-label":l["thumb-label"]})])}})}),{}}}),_e={__name:"DemoRangeSliderVertical",setup(e){const u=g([20,40]);return(l,o)=>(R(),h(x,{modelValue:f(u),"onUpdate:modelValue":o[0]||(o[0]=a=>$(u)?u.value=a:null),direction:"vertical"},null,8,["modelValue"]))}},ge={__name:"DemoRangeSliderThumbLabel",setup(e){const u=["Winter","Spring","Summer","Fall"],l=["tabler-snowflake","tabler-leaf","tabler-flame","tabler-droplet"],o=g([1,2]);return(a,i)=>(R(),h(x,{modelValue:f(o),"onUpdate:modelValue":i[0]||(i[0]=S=>$(o)?o.value=S:null),tick:u,min:"0",max:"3",step:1,"show-ticks":"always","thumb-label":"","tick-size":"4"},{"thumb-label":c(({modelValue:S})=>[t(ve,{icon:l[S]},null,8,["icon"])]),_:1},8,["modelValue"]))}},Se={__name:"DemoRangeSliderStep",setup(e){const u=g([20,40]);return(l,o)=>(R(),h(x,{modelValue:f(u),"onUpdate:modelValue":o[0]||(o[0]=a=>$(u)?u.value=a:null),step:"10"},null,8,["modelValue"]))}},he={__name:"DemoRangeSliderColor",setup(e){const u=g([10,60]);return(l,o)=>(R(),h(x,{modelValue:f(u),"onUpdate:modelValue":o[0]||(o[0]=a=>$(u)?u.value=a:null),color:"success"},null,8,["modelValue"]))}},Re={__name:"DemoRangeSliderDisabled",setup(e){const u=g([30,60]);return(l,o)=>(R(),h(x,{modelValue:f(u),"onUpdate:modelValue":o[0]||(o[0]=a=>$(u)?u.value=a:null),disabled:"",label:"Disabled"},null,8,["modelValue"]))}},ke={__name:"DemoRangeSliderBasic",setup(e){const u=g([10,60]);return(l,o)=>(R(),h(x,{modelValue:f(u),"onUpdate:modelValue":o[0]||(o[0]=a=>$(u)?u.value=a:null)},null,8,["modelValue"]))}},$e={ts:`<script setup lang="ts">
const sliderValues = ref([10, 60])
<\/script>

<template>
  <VRangeSlider v-model="sliderValues" />
</template>
`,js:`<script setup>
const sliderValues = ref([
  10,
  60,
])
<\/script>

<template>
  <VRangeSlider v-model="sliderValues" />
</template>
`},xe={ts:`<script lang="ts" setup>
const sliderValues = ref([10, 60])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    color="success"
  />
</template>
`,js:`<script setup>
const sliderValues = ref([
  10,
  60,
])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    color="success"
  />
</template>
`},ye={ts:`<script lang="ts" setup>
const slidersValues = ref([30, 60])
<\/script>

<template>
  <VRangeSlider
    v-model="slidersValues"
    disabled
    label="Disabled"
  />
</template>
`,js:`<script setup>
const slidersValues = ref([
  30,
  60,
])
<\/script>

<template>
  <VRangeSlider
    v-model="slidersValues"
    disabled
    label="Disabled"
  />
</template>
`},we={ts:`<script lang="ts" setup>
const sliderValues = ref([20, 40])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    step="10"
  />
</template>
`,js:`<script setup>
const sliderValues = ref([
  20,
  40,
])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    step="10"
  />
</template>
`},De={ts:`<script lang="ts" setup>
const seasons = ['Winter', 'Spring', 'Summer', 'Fall']
const icons = ['tabler-snowflake', 'tabler-leaf', 'tabler-flame', 'tabler-droplet']
const sliderValues = ref([1, 2])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    :tick="seasons"
    min="0"
    max="3"
    :step="1"
    show-ticks="always"
    thumb-label
    tick-size="4"
  >
    <template #thumb-label="{ modelValue }">
      <VIcon :icon="icons[modelValue]" />
    </template>
  </VRangeSlider>
</template>
`,js:`<script setup>
const seasons = [
  'Winter',
  'Spring',
  'Summer',
  'Fall',
]

const icons = [
  'tabler-snowflake',
  'tabler-leaf',
  'tabler-flame',
  'tabler-droplet',
]

const sliderValues = ref([
  1,
  2,
])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    :tick="seasons"
    min="0"
    max="3"
    :step="1"
    show-ticks="always"
    thumb-label
    tick-size="4"
  >
    <template #thumb-label="{ modelValue }">
      <VIcon :icon="icons[modelValue]" />
    </template>
  </VRangeSlider>
</template>
`},Te={ts:`<script lang="ts" setup>
const sliderValues = ref([20, 40])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    direction="vertical"
  />
</template>
`,js:`<script setup>
const sliderValues = ref([
  20,
  40,
])
<\/script>

<template>
  <VRangeSlider
    v-model="sliderValues"
    direction="vertical"
  />
</template>
`},We={__name:"range-slider",setup(e){return(u,l)=>{const o=ke,a=fe,i=Re,S=he,j=Se,I=ge,U=_e;return R(),h(be,null,{default:c(()=>[t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Basic",code:f($e)},{default:c(()=>[l[0]||(l[0]=d("p",null,[v("The "),d("code",null,"v-slider"),v(" component is a better visualization of the number input.")],-1)),t(o)]),_:1,__:[0]},8,["code"])]),_:1}),t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Disabled",code:f(ye)},{default:c(()=>[l[1]||(l[1]=d("p",null,[v("You cannot interact with "),d("code",null,"disabled"),v(" sliders.")],-1)),t(i)]),_:1,__:[1]},8,["code"])]),_:1}),t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Color",code:f(xe)},{default:c(()=>[l[2]||(l[2]=d("p",null,[v("Use "),d("code",null,"color"),v(" prop to the sets the slider color. "),d("code",null,"track-color"),v(" prop to sets the color of slider's unfilled track.")],-1)),t(S)]),_:1,__:[2]},8,["code"])]),_:1}),t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Step",code:f(we)},{default:c(()=>[l[3]||(l[3]=d("p",null,[d("code",null,"v-range-slider"),v(" can have steps other than 1. This can be helpful for some applications where you need to adjust values with more or less accuracy.")],-1)),t(j)]),_:1,__:[3]},8,["code"])]),_:1}),t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Thumb label",code:f(De)},{default:c(()=>[l[4]||(l[4]=d("p",null,[v(" Using the "),d("code",null,"tick-labels"),v(" prop along with the "),d("code",null,"thumb-label"),v(" slot, you can create a very customized solution. ")],-1)),t(I)]),_:1,__:[4]},8,["code"])]),_:1}),t(k,{cols:"12",md:"6"},{default:c(()=>[t(a,{title:"Vertical",code:f(Te)},{default:c(()=>[l[5]||(l[5]=d("p",null,[v("You can use the "),d("code",null,"vertical"),v(" prop to switch sliders to a vertical orientation. If you need to change the height of the slider, use css.")],-1)),t(U)]),_:1,__:[5]},8,["code"])]),_:1})]),_:1})}}};export{We as default};
