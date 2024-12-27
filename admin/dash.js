// Simulate dynamic data for the dashboard
document.addEventListener("DOMContentLoaded", () => {
  const totalStudents = document.getElementById("total-students");
  const attendanceRate = document.getElementById("attendance-rate");
  const avgGrade = document.getElementById("avg-grade");
  const recentActivities = document.getElementById("recent-activities");

  // Dynamically populate dashboard data (can be replaced with server data)
  totalStudents.textContent = "120";
  attendanceRate.textContent = "97%";
  avgGrade.textContent = "A";
  recentActivities.innerHTML = `
      <li>Student Mark Lee registered.</li>
      <li>Attendance updated for Mia Wong.</li>
  `;

  // Create the student enrollment chart
  const ctx1 = document
    .getElementById("studentEnrollmentChart")
    .getContext("2d");
  const studentEnrollmentChart = new Chart(ctx1, {
    type: "line",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "New Students",
          data: [30, 50, 80, 100, 120, 150],
          borderColor: "rgba(75, 192, 192, 1)",
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: { beginAtZero: true },
        y: { beginAtZero: true },
      },
    },
  });

  // Create the attendance chart
  const ctx2 = document.getElementById("attendanceChart").getContext("2d");
  const attendanceChart = new Chart(ctx2, {
    type: "bar",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Attendance Rate (%)",
          data: [92, 95, 94, 97, 96, 98],
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
});
