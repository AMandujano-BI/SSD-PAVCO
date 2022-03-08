<template>
  <app-layout title="Parts Detail">
    <div class="pt-14 mx-auto container">
      <div class="p-2">
        <div class="p-6 mb-6 border-[1px] border-[#979797] bg-[#f8fafc] mt-10">
          <div class="flex items-end justify-end gap-2">
            <button @click="reportRun()">
              <svg
                width="25"
                height="25"
                viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g fill="none" fill-rule="evenodd">
                  <path fill="#024CC5" d="M0 0h25v25H0z" />
                  <g fill="#FFF" fill-rule="nonzero">
                    <path
                      d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"
                    />
                    <path
                      d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"
                    />
                  </g>
                </g>
              </svg>
            </button>

            <button @click="reportAndPhotosRun()">
              <svg
                width="25"
                height="25"
                viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g fill="none" fill-rule="evenodd">
                  <path fill="#1E385E" d="M0 0h25v25H0z" />
                  <g fill="#FFF" fill-rule="nonzero">
                    <path
                      d="M14.584 12.249a3.253 3.253 0 0 0-3.249 3.25 3.253 3.253 0 0 0 3.25 3.249 3.253 3.253 0 0 0 3.249-3.25 3.253 3.253 0 0 0-3.25-3.249zm0 5.308a2.061 2.061 0 0 1-1.814-3.03l1.393 1.393a.596.596 0 0 0 .421.174h1.97a2.062 2.062 0 0 1-1.97 1.463zm.247-2.654-1.219-1.219a2.062 2.062 0 0 1 2.942 1.219h-1.723z"
                    />
                    <path
                      d="M18.09 4.77h-1.712a1.414 1.414 0 0 0-1.31-.885h-.925c-.23-.52-.75-.885-1.355-.885-.604 0-1.125.364-1.355.885h-.925c-.592 0-1.1.366-1.309.884H7.486C6.666 4.77 6 5.436 6 6.255v13.26C6 20.333 6.667 21 7.486 21H18.09c.819 0 1.486-.666 1.486-1.486V6.255c0-.82-.667-1.486-1.486-1.486zm-7.803.527c0-.122.1-.221.221-.221h1.396a.595.595 0 0 0 .595-.596.29.29 0 0 1 .578 0c0 .329.267.596.596.596h1.395c.122 0 .221.099.221.22v1.022c0 .122-.1.22-.221.22h-4.56a.221.221 0 0 1-.22-.22V5.297zm7.804 14.512H7.486a.295.295 0 0 1-.295-.295V6.255c0-.163.132-.295.295-.295h1.61v.358c0 .778.634 1.412 1.412 1.412h4.56c.779 0 1.412-.634 1.412-1.412V5.96h1.61c.163 0 .296.132.296.295v13.26a.295.295 0 0 1-.295.294z"
                    />
                    <path
                      d="M8.365 9.901h4.423a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM8.365 11.67h2.654a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM11.614 12.844a.595.595 0 0 0-.595-.595H8.365a.595.595 0 0 0 0 1.19h2.654a.595.595 0 0 0 .595-.595z"
                    />
                  </g>
                </g>
              </svg>
            </button>
          </div>
          <form-update-run-part
            :plateMethods="plateMethods"
            :run="run"
            :customers="customers"
          />
        </div>

        <div class="p-6 mb-6 border-[1px] border-[#979797] bg-[#f8fafc]">
          <table-part
            :run="run"
            :chromates="chromates"
            :plateTypes="plateTypes"
            :secondaryCoats="secondaryCoats"
            :topCoats="topCoats"
          />
        </div>

        <div class="p-6 mb-2 border-[1px] border-[#979797] bg-[#f8fafc]">
          <table-photos :photos="photos" :run="run" />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { ref } from "vue";
import FormUpdateRunPartVue from "./components/FormUpdateRunPart.vue";
import TablePart from "./components/TablePart.vue";
import TablePhotosVue from "./components/TablePhotos.vue";
import ModalVue from "../../Jetstream/Modal.vue";
import FormCreatePartVue from "./components/FormCreatePart.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
export default {
  props: [
    "query",
    "plateMethods",
    "run",
    "topCoats",
    "chromates",
    "plateTypes",
    "secondaryCoats",
    "customers",
  ],
  components: {
    tablePart: TablePart,
    formUpdateRunPart: FormUpdateRunPartVue,
    tablePhotos: TablePhotosVue,
    modal: ModalVue,
    formCreatePart: FormCreatePartVue,
    AppLayout: AppLayout,
  },
  setup(props) {
    const { run } = props;
    const photos = ref(run.photos);
    const id = Inertia.page.url.split("/")[2];

    const reportRun = () => {
      const currentTimeZone =  `${Intl.DateTimeFormat().resolvedOptions().timeZone}`;
      axios.post(`/run/download/${id}`, {
        zone: currentTimeZone
      }, {
        Accept: 'application/pdf',
        responseType: 'blob'
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'run_report.pdf');
        document.body.appendChild(link);
        link.click();
      }).catch( e => {
        console.log(e);
      })
    };
    const reportAndPhotosRun = () => {
      const currentTimeZone =  `${Intl.DateTimeFormat().resolvedOptions().timeZone}`;

      axios.post(`/run/downloadPlus/${id}`, {
        zone: currentTimeZone
      }, {
        Accept: 'application/pdf',
        responseType: 'blob'
      }).then((response) => {
        console.log(response);
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'run_report.pdf');
        document.body.appendChild(link);
        link.click();
      }).catch( e => {
        console.log(e);
      })
    };

    return { photos, reportRun, reportAndPhotosRun };
  },
};
</script>

