document.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".delete-button");
  deleteButtons.forEach(button => {
    button.addEventListener("click", (e) => {
      if (!confirm("Tem certeza que deseja excluir esta tarefa?")) {
        e.preventDefault();
      }
    });
  });
});
