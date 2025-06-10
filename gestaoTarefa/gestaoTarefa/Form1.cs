using System;
using System.Data.SqlClient;
using System.Windows.Forms;
using System.Text.RegularExpressions;

namespace gestaoTarefa
{
    public partial class Form1 : Form
    {
        string strConexao = @"Data Source=.\sqlexpress;Initial Catalog=dbgestaotarefas;Integrated Security=True";
        SqlConnection objConexao;
        public Form1()
        {
            InitializeComponent();
        }

        private void btnCadastrar_Click(object sender, EventArgs e)
        {
            string padrao = @"^[^@\s]+@[^@\s]+\.[^@\s]+$";
            if (Regex.IsMatch(txtEmail.Text, padrao))
            {
                objConexao = new SqlConnection(strConexao);
                SqlCommand cmd = new SqlCommand("INSERT INTO usuario VALUES (@nome,@email)", objConexao);
                cmd.Parameters.AddWithValue("@nome", txtNome.Text);
                cmd.Parameters.AddWithValue("@email", txtEmail.Text);
                objConexao.Open();
                cmd.ExecuteNonQuery();
                objConexao.Close();
                MessageBox.Show("Cadastro Concluido com sucesso!");

            } else
            {
                MessageBox.Show("Email não atende o padrão!");
            }

           
        }
    }
}
