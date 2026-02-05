<template>
  <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6 hover:border-slate-600/50 transition-colors">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-slate-400 text-sm font-medium">{{ title }}</p>
        <p class="mt-2 text-3xl font-bold text-white">
          {{ value }}<span v-if="suffix" class="text-xl text-slate-400">{{ suffix }}</span>
        </p>
      </div>
      <div :class="iconBgClass" class="p-3 rounded-xl">
        <!-- Phone Icon -->
        <svg v-if="icon === 'phone'" class="w-6 h-6" :class="iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
        <!-- Check Icon -->
        <svg v-else-if="icon === 'check'" class="w-6 h-6" :class="iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <!-- X Icon -->
        <svg v-else-if="icon === 'x'" class="w-6 h-6" :class="iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <!-- Trending Icon -->
        <svg v-else-if="icon === 'trending'" class="w-6 h-6" :class="iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  color: String,
  suffix: String,
});

const iconClass = computed(() => {
  const colors = {
    blue: 'text-blue-400',
    emerald: 'text-emerald-400',
    amber: 'text-amber-400',
    violet: 'text-violet-400',
  };
  return colors[props.color] || 'text-slate-400';
});

const iconBgClass = computed(() => {
  const colors = {
    blue: 'bg-blue-500/10',
    emerald: 'bg-emerald-500/10',
    amber: 'bg-amber-500/10',
    violet: 'bg-violet-500/10',
  };
  return colors[props.color] || 'bg-slate-500/10';
});
</script>
