<template>
  <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-5">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-5">
      <div class="p-2 bg-purple-500/10 rounded-lg">
        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
      </div>
      <div>
        <h3 class="text-base font-semibold text-white">Simulator</h3>
        <p class="text-slate-400 text-xs">Generate test data</p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- Conversation Count Slider -->
      <div>
        <label class="flex justify-between text-sm font-medium text-slate-300 mb-2">
          <span>Conversations</span>
          <span class="text-white font-bold">{{ count }}</span>
        </label>
        <input
          type="range"
          v-model="count"
          min="1"
          max="100"
          class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer slider"
        />
        <div class="flex justify-between text-xs text-slate-500 mt-1">
          <span>1</span>
          <span>50</span>
          <span>100</span>
        </div>
      </div>

      <!-- Conversion Rate Slider -->
      <div>
        <label class="flex justify-between text-sm font-medium text-slate-300 mb-2">
          <span>Conversion Rate</span>
          <span class="text-emerald-400 font-bold">{{ Math.round(conversionRate * 100) }}%</span>
        </label>
        <input
          type="range"
          v-model="conversionRatePercent"
          min="0"
          max="100"
          class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer slider"
        />
        <div class="flex justify-between text-xs text-slate-500 mt-1">
          <span>0%</span>
          <span>50%</span>
          <span>100%</span>
        </div>
      </div>

      <!-- Preview -->
      <div class="bg-slate-900/50 rounded-xl p-3 border border-slate-700/30">
        <div class="flex justify-between text-sm">
          <div class="flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
            <span class="text-slate-300">{{ convertedCount }} converted</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
            <span class="text-slate-300">{{ notConvertedCount }} missed</span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-2">
        <button
          @click="generate"
          :disabled="generating"
          class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 disabled:from-slate-600 disabled:to-slate-600 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2"
        >
          <svg v-if="generating" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          {{ generating ? 'Generating...' : 'Generate' }}
        </button>

        <button
          @click="reset"
          :disabled="resetting"
          class="w-full px-4 py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-sm font-medium rounded-xl transition-all border border-red-500/30 flex items-center justify-center gap-2"
        >
          <svg v-if="resetting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Reset All Data
        </button>
      </div>

      <!-- Status Message -->
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-1"
      >
        <div v-if="message" :class="messageClass" class="rounded-xl p-3 text-xs flex items-start gap-2">
          <svg v-if="messageType === 'success'" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ message }}</span>
        </div>
      </transition>

      <!-- Quick Actions -->
      <div class="pt-4 border-t border-slate-700/50">
        <p class="text-xs text-slate-500 mb-3">Quick Generate</p>
        <div class="grid grid-cols-3 gap-2">
          <button
            @click="quickGenerate(10)"
            :disabled="generating"
            class="px-2 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors"
          >
            +10
          </button>
          <button
            @click="quickGenerate(25)"
            :disabled="generating"
            class="px-2 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors"
          >
            +25
          </button>
          <button
            @click="quickGenerate(50)"
            :disabled="generating"
            class="px-2 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors"
          >
            +50
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const emit = defineEmits(['generated', 'reset']);

const count = ref(10);
const conversionRatePercent = ref(40);
const generating = ref(false);
const resetting = ref(false);
const message = ref('');
const messageType = ref('success');

const conversionRate = computed(() => conversionRatePercent.value / 100);
const convertedCount = computed(() => Math.round(count.value * conversionRate.value));
const notConvertedCount = computed(() => count.value - convertedCount.value);

const messageClass = computed(() => {
  return messageType.value === 'success'
    ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30'
    : 'bg-red-500/10 text-red-400 border border-red-500/30';
});

const showMessage = (text, type = 'success') => {
  message.value = text;
  messageType.value = type;
  setTimeout(() => {
    message.value = '';
  }, 4000);
};

const generate = async (customCount = null) => {
  generating.value = true;
  message.value = '';

  const genCount = customCount || count.value;

  try {
    const response = await fetch('/api/simulate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        count: genCount,
        conversion_rate: conversionRate.value,
      }),
    });

    if (!response.ok) throw new Error('Failed to generate');

    const data = await response.json();
    showMessage(`+${data.created.total} conversations (${data.created.converted} converted)`);
    emit('generated');
  } catch (e) {
    showMessage(e.message, 'error');
  } finally {
    generating.value = false;
  }
};

const quickGenerate = (num) => {
  generate(num);
};

const reset = async () => {
  if (!confirm('Delete ALL conversations? This cannot be undone.')) {
    return;
  }

  resetting.value = true;
  message.value = '';

  try {
    const response = await fetch('/api/simulate/reset', {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
      },
    });

    if (!response.ok) throw new Error('Failed to reset');

    const data = await response.json();
    showMessage(`Deleted ${data.deleted} conversations`);
    emit('reset');
  } catch (e) {
    showMessage(e.message, 'error');
  } finally {
    resetting.value = false;
  }
};
</script>

<style scoped>
.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: linear-gradient(135deg, #8b5cf6, #3b82f6);
  cursor: pointer;
  border: 2px solid #1e293b;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.slider::-moz-range-thumb {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: linear-gradient(135deg, #8b5cf6, #3b82f6);
  cursor: pointer;
  border: 2px solid #1e293b;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}
</style>
