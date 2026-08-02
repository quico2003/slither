import { useEffect, useState } from 'react';
import { Alert, Badge, Button, Form, Modal, Table } from 'react-bootstrap';
import Layout from '../components/Layout';
import api from '../services/api';

const emptyForm = { id: null, name: '', email: '', password: '', role: 'user' };

export default function Users() {
  const [users, setUsers] = useState([]);
  const [error, setError] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [form, setForm] = useState(emptyForm);
  const isEditing = Boolean(form.id);

  async function loadUsers() {
    try {
      const { data } = await api.get('/admin/users');
      setUsers(data.data);
    } catch {
      setError('No se pudieron cargar los usuarios.');
    }
  }

  useEffect(() => {
    loadUsers();
  }, []);

  function openCreate() {
    setForm(emptyForm);
    setError('');
    setShowModal(true);
  }

  function openEdit(user) {
    setForm({ id: user.id, name: user.name, email: user.email, password: '', role: user.role });
    setError('');
    setShowModal(true);
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setError('');
    try {
      if (isEditing) {
        const payload = { name: form.name, email: form.email, role: form.role };
        if (form.password) payload.password = form.password;
        await api.put(`/admin/users/${form.id}`, payload);
      } else {
        await api.post('/admin/users', form);
      }
      setShowModal(false);
      await loadUsers();
    } catch (err) {
      const message = err.response?.data?.errors
        ? Object.values(err.response.data.errors).flat().join(' ')
        : 'No se pudo guardar el usuario.';
      setError(message);
    }
  }

  async function handleDelete(user) {
    if (!confirm(`¿Eliminar a ${user.name}?`)) return;
    await api.delete(`/admin/users/${user.id}`);
    await loadUsers();
  }

  return (
    <Layout>
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h2>Usuarios</h2>
        <Button onClick={openCreate}>Nuevo usuario</Button>
      </div>

      {error && !showModal && <Alert variant="danger">{error}</Alert>}

      <Table striped bordered hover>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id}>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>
                <Badge bg={user.role === 'admin' ? 'primary' : 'secondary'}>{user.role}</Badge>
              </td>
              <td className="text-end">
                <Button size="sm" variant="outline-secondary" className="me-2" onClick={() => openEdit(user)}>
                  Editar
                </Button>
                <Button size="sm" variant="outline-danger" onClick={() => handleDelete(user)}>
                  Eliminar
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>{isEditing ? 'Editar usuario' : 'Nuevo usuario'}</Modal.Title>
        </Modal.Header>
        <Form onSubmit={handleSubmit}>
          <Modal.Body>
            {error && <Alert variant="danger">{error}</Alert>}
            <Form.Group className="mb-3">
              <Form.Label>Nombre</Form.Label>
              <Form.Control
                value={form.name}
                onChange={(e) => setForm({ ...form, name: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Email</Form.Label>
              <Form.Control
                type="email"
                value={form.email}
                onChange={(e) => setForm({ ...form, email: e.target.value })}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>{isEditing ? 'Nueva contraseña (opcional)' : 'Contraseña'}</Form.Label>
              <Form.Control
                type="password"
                value={form.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
                required={!isEditing}
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Rol</Form.Label>
              <Form.Select
                value={form.role}
                onChange={(e) => setForm({ ...form, role: e.target.value })}
              >
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
              </Form.Select>
            </Form.Group>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={() => setShowModal(false)}>
              Cancelar
            </Button>
            <Button type="submit">Guardar</Button>
          </Modal.Footer>
        </Form>
      </Modal>
    </Layout>
  );
}
