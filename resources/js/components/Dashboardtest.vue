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
</template> -->

<!-- <template> -->

  <div class="d-flex align-center flex-column">
    <div class="text-subtitle-2"></div>
    <h2>Theses Repository</h2>

    <v-app style = "background-color: transparent;">

    <!-- <v-card
      v-for="thesis in data" :key="thesis.id"
      :title="thesis.form_title"
      :subtitle="thesis.description"
      width="800"
    >
    </v-card> -->

    <div style = "padding-bottom: 10px;">
    <v-card v-for="thesis in data" :key="thesis.id"  width="800" hover>
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
    <v-card-item>

      <!-- <v-select
        v-model="thesis.status"
        :items="['archivedd', 'unarchived']"
        label="Status"
        outlined
        @change="updateStatus(thesis)"
        width="800"
      >
      </v-select> -->
    </v-card-item>

    <iframe v-if="previewFileUrl" :src="previewFileUrl"></iframe>

  </v-card>

</div>

<div>
  <v-card
    class="mx-auto mb-2"
    max-width="800"
    v-for="submission in data"
    :key="submission.id"
  >

    <v-img
      src="https://th.bing.com/th/id/OIP.EZeP9vSGOADD1MSdiDctcgHaE7?rs=1&pid=ImgDetMain"
      height="150px"
      cover
    ></v-img>

    <!-- <v-card-title>
      {{ submission.form_title }}
    </v-card-title> -->
    <v-card-title class = "pa-4 pb-0 pr-0">
          <div class="d-flex justify-space-between align-center">
          {{ submission.form_title }}
          <!-- v-card-actions for placing the print button at the right top -->
          <v-card-actions>
            <!-- Use a spacer to push the button to the right -->
            <!-- <v-spacer></v-spacer> -->

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
    <!-- <v-chip class ="custome-chip" :color="getChipColor(submissionPost.section)"
      style="margin-bottom: 5px; font-size: 10px; height: 20px;">
      {{ submissionPost.section }}
    </v-chip> -->
    <v-chip class ="custome-chip ml-4" color="yellow"
      style="margin-bottom: 5px; font-size: 12px; padding: 15px; height: 20px;">
      Machine Learning
    </v-chip>

    <v-card-subtitle>
      Author: {{ submission.student_name }}
    </v-card-subtitle>

    <v-card-actions>
      <v-btn
        color="orange-lighten-2"
        variant="text"
      >
        Explore
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn
        :icon="show ? 'mdi-chevron-up' : 'mdi-chevron-down'"
        @click="show = !show"
      ></v-btn>
    </v-card-actions>

    <v-expand-transition>
      <div v-show="show">
        <v-divider style="margin: 1px;"></v-divider>
        <v-card-text style="padding-top: 1px;">
          {{ submission.description }}
        </v-card-text>
      </div>
    </v-expand-transition>
  </v-card>
<br>
</div>

<!-- </template> -->
<!-- <script>
  export default {
    data: () => ({
      show: false,
    }),
  }
</script> -->
  <div class="testcolour">
      <v-col
        v-for="(variant, i) in variants"
        :key="i"
        cols="auto"
      >
        <v-card
            class="mx-auto"
            max-width="344"
            :color="color"
            :variant="variant"
            hover
          >

          <v-card-item>
            <div>
              <div class="text-overline mb-1">
                <v-card-title>
                <div class="text-h6 mb-1">Headline</div>
                <div class="text-caption">
                  Greyhound divisely hello coldly fonwderfully
                </div>
              </v-card-title>

              {{ variant }}
              </div>
              <div class="text-h6 mb-1">Headline</div>
              <div class="text-caption">
                Greyhound divisely hello coldly fonwderfully
              </div>
            </div>
          </v-card-item>
          <v-card-actions>
            <v-btn> Button </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </div>

    <div class="mt-4 text-subtitle-2">With markup</div>

    <v-card max-width="400">
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

  // const fetchData = () => {
  // axios.get("/api/testvuedata")
  //   .then((response) => {
  //     console.log(response.data);
  //     data.value = response.data;
  //   })
  //   .catch(error => {
  //     console.error('Error fetching data:', error);
  //   });
  // };

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

  try {
      const studentsResponse = await axios.get('/api/students'); // Adjust the endpoint
      console.log(studentsResponse);
      students = studentsResponse.data;
      console.log(students);

      return submissions.map(submission => {
        const student = students.find(student => student.stu_id === submission.student_id);
        return {
          id: submission.id,
          form_title: submission.form_title,
          description: submission.description,
          form_files: submission.form_files,
          student_id: submission.student_id,
          student_name: student ? (student.user ? student.user.name : 'Unknown Student') : 'Unknown Student',
        };
      });
    } catch (error) {
      console.error('Error fetching student data:', error);
      return submissions; // Return original data in case of an error
    }
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

// Fetch data when the component is created
onMounted(() => {
  fetchData();
});

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