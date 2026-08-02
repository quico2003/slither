import { useCallback, useEffect, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { Alert, Button, Card, Col, ProgressBar, Row, Spinner } from 'react-bootstrap';
import Layout from '../components/Layout';
import api from '../services/api';

export default function Gallery() {
  const [media, setMedia] = useState([]);
  const [error, setError] = useState('');
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);

  async function loadMedia() {
    try {
      const { data } = await api.get('/user/media');
      setMedia(data.data);
    } catch {
      setError('No se pudo cargar tu galería.');
    }
  }

  useEffect(() => {
    loadMedia();
  }, []);

  const onDrop = useCallback(async (acceptedFiles) => {
    setError('');
    for (const file of acceptedFiles) {
      const formData = new FormData();
      formData.append('file', file);
      setUploading(true);
      setProgress(0);
      try {
        await api.post('/user/media', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (evt) => {
            setProgress(Math.round((evt.loaded * 100) / evt.total));
          },
        });
      } catch {
        setError(`No se pudo subir ${file.name}.`);
      }
    }
    setUploading(false);
    await loadMedia();
  }, []);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop,
    accept: {
      'image/*': [],
      'video/*': [],
    },
  });

  async function handleDelete(item) {
    if (!confirm(`¿Eliminar ${item.name}?`)) return;
    await api.delete(`/user/media/${item.id}`);
    await loadMedia();
  }

  return (
    <Layout>
      <h2 className="mb-3">Mi galería</h2>

      {error && <Alert variant="danger">{error}</Alert>}

      <div
        {...getRootProps()}
        className="border border-2 border-dashed rounded p-5 text-center mb-4"
        style={{ cursor: 'pointer', background: isDragActive ? '#f0f0f0' : '#fafafa' }}
      >
        <input {...getInputProps()} />
        {uploading ? (
          <>
            <Spinner animation="border" size="sm" className="me-2" />
            Subiendo... <ProgressBar now={progress} label={`${progress}%`} className="mt-2" />
          </>
        ) : isDragActive ? (
          <p className="mb-0">Suelta los archivos aquí...</p>
        ) : (
          <p className="mb-0">Arrastra fotos o vídeos aquí, o haz clic para seleccionarlos</p>
        )}
      </div>

      <Row xs={2} md={4} className="g-3">
        {media.map((item) => (
          <Col key={item.id}>
            <Card>
              {item.mime_type?.startsWith('video') ? (
                <video src={item.full_url} className="card-img-top" style={{ height: 150, objectFit: 'cover' }} controls />
              ) : (
                <Card.Img
                  variant="top"
                  src={item.full_url}
                  style={{ height: 150, objectFit: 'cover' }}
                />
              )}
              <Card.Body className="p-2">
                <Card.Text className="text-truncate small mb-2">{item.name}</Card.Text>
                <Button size="sm" variant="outline-danger" className="w-100" onClick={() => handleDelete(item)}>
                  Eliminar
                </Button>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>

      {media.length === 0 && !uploading && <p className="text-muted">Todavía no has subido nada.</p>}
    </Layout>
  );
}
