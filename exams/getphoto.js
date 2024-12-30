document.addEventListener("DOMContentLoaded", function () {
  fetchContent();

  async function fetchContent() {
      try {
          // Fetch data from the server
          const response = await fetch('./fetchresults.php');
          if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
          }
          const data = await response.json();

          // Update Exam Schedule
          const examList = document.getElementById("exam-list");
          examList.innerHTML = data.exams.map(exam => `
              <div>
                  <strong>${exam.subject}</strong> - ${exam.date} (Semester: ${exam.semester})
              </div>
          `).join("");

          // Update Upcoming Exams
          const upcomingExams = document.getElementById("upcoming-exams");
          upcomingExams.innerHTML = data.exams.length
              ? `Next Exam: ${data.exams[0].subject} on ${data.exams[0].date}`
              : "No upcoming exams";

          // Update Results Section
          const resultsList = document.getElementById("results-list");
          resultsList.innerHTML = data.results.map(result => `
              <div>
                  <strong>${result.subject}</strong>: ${result.marks} marks
              </div>
          `).join("");

      } catch (error) {
          console.error("Error fetching content:", error);
      }
  }
});
