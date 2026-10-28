<template>
  <div class="mb-3">
    <label class="text-[#3b4559] font-semibold">Plate Type</label>
    <div
      v-for="(layer, index) in layers"
      :key="index"
      class="mb-3"
      :class="{ 'border-l-4 border-[#3b4559]/20 pl-2': layers.length > 1 }"
    >
      <div
        v-if="layers.length > 1"
        class="flex justify-between items-center text-[#3b4559] text-sm"
      >
        <span>Layer {{ index + 1 }}</span>
        <button
          type="button"
          class="font-bold px-1 rounded-md"
          title="Remove layer"
          @click="removeLayer(index)"
        >
          X
        </button>
      </div>
      <div class="flex flex-col md:flex-row w-full justify-between gap-2">
        <div class="w-full">
          <multi-select
            :options="plateTypes"
            class="w-full"
            v-model="layer.plate_type_id"
            :searchable="true"
            placeholder="Select Plate Type"
          />
        </div>
        <div class="w-full flex justify-between gap-2">
          <div class="w-full md:w-[210px]">
            <multi-select
              :options="thicknessUnits"
              class="w-full"
              v-model="layer.thickness_unit"
              :searchable="true"
              placeholder="Select Type "
            />
          </div>
          <div>
            <input
              type="number"
              step=".01"
              class="w-[80px] disabled:bg-gray-100"
              :disabled="!layer.plate_type_id"
              v-model="layer.thickness"
            />
          </div>
        </div>
      </div>
      <div class="flex gap-2 justify-end mt-1">
        <div>
          <input
            type="number"
            step=".01"
            class="w-[60px] disabled:bg-gray-100"
            :disabled="!layer.plate_type_id"
            v-model="layer.per"
          />
          <span class="text-center block pt-1 text-[#3b4559]">%</span>
        </div>
        <div>
          <input
            type="number"
            step=".01"
            class="w-[60px] disabled:bg-gray-100"
            :disabled="!layer.plate_type_id"
            v-model="layer.temp"
          />
          <span class="text-center block pt-1 text-[#3b4559]">°F</span>
        </div>
        <div>
          <input
            type="number"
            step=".01"
            class="w-[60px] disabled:bg-gray-100"
            :disabled="!layer.plate_type_id"
            v-model="layer.ph"
          />
          <span class="text-center block pt-1 text-[#3b4559]">pH</span>
        </div>
        <div>
          <input
            type="number"
            step=".01"
            class="w-[60px] disabled:bg-gray-100"
            :disabled="!layer.plate_type_id"
            v-model="layer.diptime"
          />
          <span class="text-center block pt-1 text-[#3b4559]">sec</span>
        </div>
      </div>
    </div>
    <button
      v-if="layers.length < maxLayers"
      type="button"
      class="text-primary font-semibold hover:underline"
      @click="addLayer"
    >
      + Add plate layer
    </button>
  </div>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import { emptyPlateLayer, MAX_PLATE_LAYERS } from "../composables/usePlateLayers";

export default {
  props: ["layers", "plateTypes"],
  components: {
    multiSelect: Multiselect,
  },
  setup(props) {
    const thicknessUnits = [
      { value: 1, label: "microns" },
      { value: 2, label: "mils" },
    ];
    const addLayer = () => props.layers.push(emptyPlateLayer());
    const removeLayer = (index) => props.layers.splice(index, 1);

    return { thicknessUnits, addLayer, removeLayer, maxLayers: MAX_PLATE_LAYERS };
  },
};
</script>
