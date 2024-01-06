<template >

    <!-- <ul>
      <li v-for="(value, key) in data" :key="key">
        {{ key }}: {{ value }}
      </li>
    </ul> -->
  <div class="d-flex align-center flex-column">
    <div class="text-subtitle-2"></div>
    <h2 style="margin-top: 50px;margin-bottom: 30px;">Theses Repository</h2>

    <v-app style = "background-color: transparent;">
    <div>
      <v-card
        class="mx-auto mb-3"
        width="600"
        v-for="submission in data"
        :key="submission.id"
      >
          <!-- src="https://th.bing.com/th/id/OIP.EZeP9vSGOADD1MSdiDctcgHaE7?rs=1&pid=ImgDetMain" -->

        <!-- <v-img
          src="/admindash/img/ML.jpg"
          height="150px"
          cover
          style="position: relative; bottom:0; width: 100%; overflow: hidden;"

        ></v-img> -->
        <v-img
          :src="getImgSource(submission.thesis_type)"
          height="150px"
          cover
          style="position: relative; bottom: 0; width: 100%; overflow: hidden;"
        ></v-img>

        <v-card-title class = "pl-4 pt-0 pb-0 pr-0">
              <div class="d-flex justify-space-between align-center">
              {{ submission.thesis_title }}
              <!-- v-card-actions for placing the print button at the right top -->
              <v-card-actions>
                <!-- Use a spacer to push the button to the right -->

                <v-btn icon @click="previewFile(submission.form_files)">
                  <v-icon>mdi-eye</v-icon>
                </v-btn>
                <!-- Print button -->
                <v-btn icon @click="downloadFile(submission.form_files)">
                  <v-icon>mdi-file-download</v-icon>
                </v-btn>
              </v-card-actions>
              </div>

            </v-card-title>

            <v-chip
              v-for="(type, index) in submission.thesis_type.split(',')"
              :key="index"
              :class="getChipClass(type)"
              :color="getChipColor(type)"
              style="margin-left:10px;margin-bottom: 5px; font-size: 12px; padding: 15px; height: 20px;"
            >
              {{ getFormattedType(type) }}
            </v-chip>
        <v-card-subtitle class="mt-2 ml-2">
          Author: {{ submission.student_name }}
        </v-card-subtitle>

        <v-card-actions>
          <v-btn
            color="orange-lighten-2"
            variant="text"
            class="ml-2"
          >
            Explore
          </v-btn>

          <v-spacer></v-spacer>

          <!-- <v-btn
            :icon="submission.show ? 'mdi-chevron-up' : 'mdi-chevron-down'"
            @click="toggleCard(submission)"
          ></v-btn> -->
          <v-btn
            :icon="show ? 'mdi-chevron-up' : 'mdi-chevron-down'"
            @click="show = !show"
          ></v-btn>
        </v-card-actions>

        <v-expand-transition>
          <div v-show="show">
            <v-divider style="margin: 1px;"></v-divider>
            <v-card-text class="pt-1 ml-2">
              {{ submission.description }}
            </v-card-text>
          </div>
        </v-expand-transition>
      </v-card>
      <v-pagination
        v-model="currentPage"
        :length="totalPages"
        @input="goToPage"
      ></v-pagination>
    </div>
    <!-- <v-pagination
    v-model="currentPage"
    :length="totalPages"
    @input="goToPage"
  ></v-pagination> -->

    </v-app>
  </div>
</template>

<script setup>
  import { ref, onMounted  } from 'vue'
  import axios from 'axios';

  const variants = ['elevated', 'flat', 'tonal', 'outlined']
  // const color = ref('#EFD469')
  const color = ref('#FFD700')
  const show = ref(false);

  const data = ref([]);
  const previewFileUrl = ref(null);  // Add this line

  const fetchData = async () => {
    try {
      const response = await axios.get('/api/testvuedata');
      console.log("repsonese", response);
      data.value = await enrichDataWithStudentNames(response.data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };

  const enrichDataWithStudentNames = async (submissions) => {
  let students; // Declare the variable here
  // Add show property for each submission
  const show = ref([]);

  try {
      const studentsResponse = await axios.get('/api/students'); // Adjust the endpoint
      console.log(studentsResponse);
      students = studentsResponse.data;
      console.log(students);

      return submissions.map(submission => {
        const student = students.find(student => student.stu_id === submission.student_id);
        return {
          id: submission.id,
          thesis_title: submission.thesis_title,
          description: submission.thesis_abstract,
          form_files: submission.thesis_file,
          thesis_type:submission.thesis_type,
          student_id: submission.student_id,
          student_name: student ? (student.user ? student.user.name : 'Unknown Student') : 'Unknown Student',
        };
      });
    } catch (error) {
      console.error('Error fetching student data:', error);
      // return submissions; // Return original data in case of an error
      return submissions.map(() => ({ show: false }));

    }
  };
  const toggleCard = (submission) => {
    submission.show = !submission.show;
  };

const previewFile = (formFile) => {
  const fileObject = JSON.parse(formFile);
  if (Array.isArray(fileObject) && fileObject.length > 0) {
    const filePath = fileObject[0].path;
    console.log('File Path:', filePath);

  // Assuming file is a PDF and its path is stored in file.path
    // previewFileUrl.value = `https://mozilla.github.io/pdf.js/web/viewer.html?file=${(filePath)}`;

    // doesnt work yet....
    previewFileUrl.value = `https://mozilla.github.io/pdf.js/web/viewer.html?file=${window.location.origin}/storage/${filePath}`;

    // Assuming doc is a docx
    // previewFileUrl.value = `https://docs.google.com/viewer?url=${(filePath)}&embedded=true`;

  }
};

const downloadFile = (formFile) => {
  console.log(formFile);
  const fileObject = JSON.parse(formFile);
  console.log('fileObject:', fileObject);

  if (Array.isArray(fileObject) && fileObject.length > 0) {
    const filePath = fileObject[0].path;
    console.log('File Path:', filePath);
    console.log(encodeURIComponent(filePath));
    window.location.href = `/api/download/${(filePath)}`;
  } else {
    console.error('Form file is undefined or empty.');
  }
};

const getFormattedType = (type) => {
  console.log('getFormattedType');

    switch (type) {
      case 'web_development':
        return 'Website Development';
      case 'mobile_development':
        return 'Mobile App';
      case 'machine_learning':
        return 'Machine Learning';
      case 'data_analytics':
        return 'Data Analytics';
      case 'dashboard_analytics':
        return 'Dashboard Analytics';
      default:
        return type;
    }
  };

  const getChipColor = (type) => {
    switch (type) {
      case 'web_development':
        return 'green';
      case 'mobile_development':
        return 'pink';
      case 'machine_learning':
        return 'blue';
      case 'data_analytics':
        return 'blue';
      case 'dashboard_analytics':
        return 'green';
      default:
        return 'yellow'; // Default color
    }
  };
  const getChipClass = (type) => {
    // You can add additional classes or logic based on type if needed
    return 'custom-chip ml-3 mr-1';
  };
// Fetch data when the component is created
onMounted(() => {
  fetchData();
});

const getImgSource = (thesisType) => {
  const firstType = thesisType.split(',')[0].trim();
  return imageSources[firstType] || '/img/default-image.jpg';
};

const imageSources = {
  web_development: '/admindash/img/ML.jpg',
  mobile_development: '/img/mobile_development.jpg',
  machine_learning: '/admindash/img/ML.jpg',
  data_analytics: '/admindash/img/DA.jpg',
  dashboard_analytics: '/img/dashboard_analytics.jpg',
};

// paginations!!!!
// const pageSize = 5; // Set the number of cards per page
//   const currentPage = ref(1);

//   const totalPages = computed(() => Math.ceil(data.value.length / pageSize));

  // const paginatedData = computed(() => {
  //   const startIndex = (currentPage.value - 1) * pageSize;
  //   const endIndex = startIndex + pageSize;
  //   return data.value.slice(startIndex, endIndex);
  // });

  // const goToPage = (page) => {
  //   if (page >= 1 && page <= totalPages.value) {
  //     currentPage.value = page;
  //   }
  // };

</script>

<style>
.v-card-title,
.v-card-subtitle,
.v-card-text {
  color: black;
}

.v-card {
  background-color: yellow;
}

.v-select-list .v-list-item__title {
  font-size: 10px;
}


</style>