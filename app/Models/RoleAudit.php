namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAudit extends Model
{
    protected $fillable = ['action'];

    public static function log($action)
    {
        self::create(['action' => $action]);
    }
}
