<template >

    <ul>
      <li v-for="(value, key) in data" :key="key">
        {{ key }}: {{ value }}
      </li>
    </ul>

    <!-- <template>
    <div>
      <v-card v-for="item in dashboardData" :key="item.id">
        <v-card-title>{{ dashboardData.title }}</v-card-title>
        <v-card-subtitle>{{ dashboardData.subtitle }}</v-card-subtitle>
        <v-card-text>{{ dashboardData.description }}</v-card-text>
      </v-card>
    </div>
</templSate> -->

<!-- <template> -->

  <div class="d-flex align-center flex-column">
    <div class="text-subtitle-2"></div>
    <h2>Theses</h2>

    <v-app style = "background-color: transparent;">

    <!-- <v-card
      v-for="thesis in data" :key="thesis.id"
      :title="thesis.form_title"
      :subtitle="thesis.description"
      width="800"
    >
    </v-card> -->

    <div class="text-subtitle-2">

    <!-- <v-card width="400"> -->
      <!-- <template v-slot:title>
        {{ thesis.form_title }}
        <v-spacer></v-spacer>
                <v-btn icon @click="downloadFile(thesis.form_files)">
            <v-icon>mdi-file-download</v-icon>
          </v-btn>
      </template> -->
      <v-card v-for="thesis in data" :key="thesis.id"  width="800">
      <v-card-item style = "padding-top: 0px;">

        <v-card-title class = "pa-0">
          <div class="d-flex justify-space-between align-center">
          {{ thesis.form_title }}
          <!-- v-card-actions for placing the print button at the right top -->
          <v-card-actions>
            <!-- Use a spacer to push the button to the right -->
            <!-- <v-spacer></v-spacer> -->

            <v-btn icon @click="previewFile(thesis.form_files)">
              <v-icon>mdi-eye</v-icon>
            </v-btn>
            <!-- Print button -->
            <v-btn icon @click="downloadFile(thesis.form_files)">
              <v-icon>mdi-file-download</v-icon>
            </v-btn>
          </v-card-actions>
          </div>

        </v-card-title>

        <v-card-subtitle>
          {{ thesis.description }}
        </v-card-subtitle>
      </v-card-item>

      <v-card-text>
        This is content
      </v-card-text>

      <!-- <template v-slot:subtitle>
        {{ thesis.description }}
      </template>

      <template v-slot:text> -->
      <!-- </template> -->
    <!-- </v-card> -->

    <iframe v-if="previewFileUrl" :src="previewFileUrl"></iframe>

  </v-card>

    <div class="mt-4 text-subtitle-2">With markup</div>

    <v-card width="400">
      <v-card-item>
        <v-card-title>This is a title</v-card-title>

        <v-card-subtitle>This is a subtitle</v-card-subtitle>
      </v-card-item>

      <v-card-text>
        This is content
      </v-card-text>
    </v-card>

    <!-- <v-card
      v-for="thesis in data" :key="thesis.id"
      width="700"
    >
      <template v-slot:title>
        {{ thesis.form_title }}
      </template>

      <template v-slot:subtitle>
        {{ thesis.description }}
      </template>
    </v-card> -->
    </div>
  </v-app>

  </div>
</template>

<script setup>
  import { ref } from 'vue'
  import axios from 'axios';

  const variants = ['elevated', 'flat', 'tonal', 'outlined']
  const color = ref('#EFD469')

  const data = ref([]);

  const fetchData = () => {
  axios.get("/api/testvuedata")
    .then((response) => {
      console.log(response.data);
      data.value = response.data;
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });
};

const downloadFile = (formFile) => {
  console.log(formFile);
  const fileObject = JSON.parse(formFile);
  console.log('fileObject:', fileObject);

  if (Array.isArray(fileObject) && fileObject.length > 0) {
    const filePath = fileObject[0].path;
    console.log('File Path:', filePath);
    console.log(encodeURIComponent(filePath));
    window.location.href = `api/download/${encodeURIComponent(filePath)}`;
  } else {
    console.error('Form file is undefined or empty.');
  }
};

// Fetch data when the component is created
created(() => {
  fetchData();
});

</script>
// export default {
//   // props: ['server-data'],
//   // props: {
//   //   data: {
//   //     type: Array,
//   //     required: true,
//   //   },
//   // },
// data() {
//     return {
//       data: [],
//       previewFileUrl: null,

//     };
//   },
//   created() {
//       axios.get("/api/testvuedata").then((response) => {
//             console.log(response.data);
//             this.data = response.data;
//     })
//     .catch(error => {
//           console.error('Error fetching data:', error);
//         });
//   },
//   methods: {
//     // Function to handle file download
//     // downloadFile(formFile) {
//     //   console.log('Thesis Object:', formFile);

//     //  // Check if formFile is defined and not an empty array
//     // //  if (typeof formFile[0] === 'string' && formFile[0].trim() !== '') {
//     //   const fileObject = JSON.parse (formFile);
//     //   console.log('Parsed Thesis Object:', fileObject);

//     //   console.log(fileObject);
//     //   const filePath = fileObject[0].path;
//     //   console.log("filepath" + filePath);

//     //   // Extract the filename from the filePath
//     //   const filename = filePath.split('/').pop();
//     //   console.log("filename" + filename);

//     //   // Check if filename is defined
//     //   if (filename) {
//     //     // Construct the full URL to download the file
//     //     const fileUrl = `api/download/${filePath}`;
//     //     // Trigger download by creating an invisible link and simulating a click
//     //     console.log(fileUrl);

//     //     const link = document.createElement('a');
//     //     link.href = fileUrl;
//     //     link.target = '_blank';
//     //     // link.download = filePath;
//     //     link.download = filename;
//     //     link.click();
//     //   } else {
//     //     // Handle the case when the path is not available
//     //     console.error('File path not available.');
//     //   }
//     // }
//     // else {
//     //   // Handle the case when formFile is undefined or an empty array
//     //   console.error('Form file is undefined or empty.');
//     // }

//     downloadFile(formFile) {
//       // Parse the formFile string into an object
//       console.log(formFile);
//       const fileObject = JSON.parse(formFile);
//       console.log('fileObject:', fileObject);

//       // Check if fileObject is an array and not empty
//       if (Array.isArray(fileObject) && fileObject.length > 0) {
//           // Get the filePath from the first object in the array
//           const filePath = fileObject[0].path;
//           console.log('File Path:', filePath);
//           console.log(encodeURIComponent(filePath));
//           // Download the file
//           // window.location.href = `api/download/${encodeURIComponent(filePath)}`;
//           window.location.href = `api/download/${(filePath)}`;

//       } else {
//           console.error('Form file is undefined or empty.');
//       }
//     }
//     }
// // }
// }

</script>
<!-- // resources/js/components/Dashboardtest.vue
<template>
  <div>
    <v-app>

    <v-card v-for="item in initialData" :key="item.id">
      <v-card-title>{{ item.title }}</v-card-title>
      <v-card-subtitle>{{ item.subtitle }}</v-card-subtitle>
      <v-card-text>{{ item.description }}</v-card-text>
    </v-card>
    </v-app>

  </div>
</template>

<script type="module">
import axios from 'axios';

export default {
  props: {
    initialData: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      dashboardData: [],
    };
  },
  created() {
    // Fetch data using a web route
    this.fetchDashboardData();

  },
  methods: {
    fetchDashboardData() {
      // Make an AJAX request to your Laravel web route
      axios.get('/testvue')
        .then(response => {
          this.dashboardData = response.data;
          console.log(response.data);
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    },
  },
};
</script>
