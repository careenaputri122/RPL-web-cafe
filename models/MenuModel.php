<?php
require_once 'config/database.php';

class MenuModel {

    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            $this->db = null;
        }
    }

    /**
     * Ambil semua menu, dengan opsional filter kategori & search
     */
    public function getAll(string $kategori = 'semua', string $search = ''): array {
        if ($this->db) {
            try {
                $sql    = "SELECT * FROM menu WHERE 1=1";
                $params = [];

                if ($kategori !== 'semua') {
                    $sql     .= " AND kategori = :kategori";
                    $params[':kategori'] = $kategori;
                }
                if ($search !== '') {
                    $sql     .= " AND (nama LIKE :search OR deskripsi LIKE :search)";
                    $params[':search'] = "%{$search}%";
                }

                $sql .= " ORDER BY kategori, id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                // Fallback ke demo data jika query gagal
            }
        }

        return $this->_filterDemoData($this->_demoData(), $kategori, $search);
    }

    /**
     * Ambil menu populer (digunakan di Home)
     */
    public function getPopuler(int $limit = 4): array {
        if ($this->db) {
            try {
                $stmt = $this->db->prepare(
                    "SELECT * FROM menu WHERE status = 'tersedia' ORDER BY id LIMIT :limit"
                );
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {}
        }

        return array_slice($this->_demoData(), 0, $limit);
    }

    // ──────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────

    private function _filterDemoData(array $data, string $kategori, string $search): array {
        return array_values(array_filter($data, function ($item) use ($kategori, $search) {
            $matchKat  = ($kategori === 'semua') || ($item['kategori'] === $kategori);
            $matchSrch = ($search === '')
                || (stripos($item['nama'], $search) !== false)
                || (stripos($item['deskripsi'], $search) !== false);
            return $matchKat && $matchSrch;
        }));
    }

    private function _demoData(): array {
        return [
            // ── MAKANAN ──────────────────────────────────────────────────────
            [
                'id'        => 1,
                'nama'      => 'Nasi Goreng Spesial',
                'deskripsi' => 'Nasi goreng dengan telur, ayam suwir, dan bumbu rempah pilihan yang kaya rasa',
                'harga'     => 35000,
                'kategori'  => 'makanan',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 2,
                'nama'      => 'Ayam Bakar Madu',
                'deskripsi' => 'Ayam bakar dengan marinasi madu dan kecap, disajikan dengan lalapan segar',
                'harga'     => 42000,
                'kategori'  => 'makanan',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 3,
                'nama'      => 'Mie Goreng Seafood',
                'deskripsi' => 'Mie goreng dengan udang, cumi, dan sayuran segar dalam bumbu spesial',
                'harga'     => 38000,
                'kategori'  => 'makanan',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 4,
                'nama'      => 'Soto Ayam Lamongan',
                'deskripsi' => 'Soto ayam khas Lamongan dengan kuah bening segar dan pelengkap lengkap',
                'harga'     => 28000,
                'kategori'  => 'makanan',
                'status'    => 'habis',
                'gambar'    => 'https://images.unsplash.com/photo-1555126634-323283e090fa?w=400&h=400&fit=crop',
            ],
            // ── MINUMAN ──────────────────────────────────────────────────────
            [
                'id'        => 5,
                'nama'      => 'Es Kopi Susu',
                'deskripsi' => 'Kopi susu segar dengan espresso premium dan susu segar pilihan',
                'harga'     => 22000,
                'kategori'  => 'minuman',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 6,
                'nama'      => 'Matcha Latte',
                'deskripsi' => 'Matcha premium Jepang dengan susu oat creamy, disajikan dingin atau panas',
                'harga'     => 28000,
                'kategori'  => 'minuman',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 7,
                'nama'      => 'Jus Alpukat',
                'deskripsi' => 'Jus alpukat segar dengan susu kental manis dan es batu pilihan',
                'harga'     => 20000,
                'kategori'  => 'minuman',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 8,
                'nama'      => 'Teh Tarik',
                'deskripsi' => 'Teh tarik khas Malaysia dengan susu evaporasi yang creamy dan harum',
                'harga'     => 15000,
                'kategori'  => 'minuman',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=400&fit=crop',
            ],
            // ── DESSERT ──────────────────────────────────────────────────────
            [
                'id'        => 9,
                'nama'      => 'Tiramisu Slice',
                'deskripsi' => 'Tiramisu klasik Italia dengan mascarpone premium dan espresso shot',
                'harga'     => 32000,
                'kategori'  => 'dessert',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 10,
                'nama'      => 'Lava Cake Coklat',
                'deskripsi' => 'Molten chocolate lava cake dengan isian coklat cair dan es krim vanilla',
                'harga'     => 35000,
                'kategori'  => 'dessert',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=400&h=400&fit=crop',
            ],
            [
                'id'        => 11,
                'nama'      => 'Pancake Stack',
                'deskripsi' => 'Tumpukan pancake fluffy dengan maple syrup, butter, dan buah segar',
                'harga'     => 30000,
                'kategori'  => 'dessert',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=400&fit=crop',
            ],
            // ── PAKET ────────────────────────────────────────────────────────
            [
                'id'        => 12,
                'nama'      => 'Paket Hemat Duo',
                'deskripsi' => 'Nasi goreng + Es kopi susu, hemat 10rb dari harga normal',
                'harga'     => 55000,
                'kategori'  => 'makanan',
                'status'    => 'tersedia',
                'gambar'    => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop',
            ],
        ];
    }
}
