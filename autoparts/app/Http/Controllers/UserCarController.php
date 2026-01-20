<?php

namespace App\Http\Controllers;

use App\Models\UserCar;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarYear;
use App\Models\Product;
use Illuminate\Http\Request;

class UserCarController extends Controller
{
    public function index()
    {
        $cars = auth()->user()->cars()->with(['make', 'model'])->get();
        $makes = CarMake::active()->orderBy('name')->get();

        return view('cars.index', compact('cars', 'makes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'make_id' => 'required|exists:car_makes,id',
            'model_id' => 'required|exists:car_models,id',
            'year' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'nickname' => 'nullable|string|max:100',
            'vin' => 'nullable|string|max:17',
            'plate_number' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'mileage' => 'nullable|integer|min:0',
        ]);

        $car = auth()->user()->cars()->create($request->all());

        if ($request->is_default) {
            auth()->user()->cars()->where('id', '!=', $car->id)->update(['is_default' => false]);
            $car->update(['is_default' => true]);
        }

        return back()->with('success', 'تم إضافة السيارة بنجاح');
    }

    public function update(Request $request, UserCar $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nickname' => 'nullable|string|max:100',
            'vin' => 'nullable|string|max:17',
            'plate_number' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'mileage' => 'nullable|integer|min:0',
        ]);

        $car->update($request->only(['nickname', 'vin', 'plate_number', 'color', 'mileage']));

        return back()->with('success', 'تم تحديث بيانات السيارة');
    }

    public function destroy(UserCar $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $car->delete();

        return back()->with('success', 'تم حذف السيارة');
    }

    public function setDefault(UserCar $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->cars()->update(['is_default' => false]);
        $car->update(['is_default' => true]);

        return back()->with('success', 'تم تعيين السيارة الافتراضية');
    }

    public function compatibleProducts(UserCar $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $products = Product::active()
            ->inStock()
            ->whereHas('compatibilities', function($q) use ($car) {
                $q->where('car_make_id', $car->make_id)
                  ->where('car_model_id', $car->model_id)
                  ->where(function($q2) use ($car) {
                      $q2->whereNull('year_from')
                         ->orWhere('year_from', '<=', $car->year);
                  })
                  ->where(function($q2) use ($car) {
                      $q2->whereNull('year_to')
                         ->orWhere('year_to', '>=', $car->year);
                  });
            })
            ->with(['store', 'primaryImage', 'category'])
            ->paginate(24);

        return view('cars.compatible-products', compact('car', 'products'));
    }

    public function getModels(CarMake $make)
    {
        $models = CarModel::where('make_id', $make->id)
            ->active()
            ->orderBy('name')
            ->get();

        return response()->json($models);
    }

    public function getYears(CarModel $model)
    {
        $years = CarYear::where('model_id', $model->id)
            ->orderByDesc('year')
            ->pluck('year');

        return response()->json($years);
    }

    public function maintenanceSchedule(UserCar $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $schedule = [
            ['km' => 5000, 'service' => 'تغيير زيت المحرك والفلتر', 'products' => ['زيت محرك', 'فلتر زيت']],
            ['km' => 10000, 'service' => 'فحص الفرامل', 'products' => ['فحمات فرامل']],
            ['km' => 15000, 'service' => 'تغيير فلتر الهواء', 'products' => ['فلتر هواء']],
            ['km' => 20000, 'service' => 'تغيير شمعات الإشعال', 'products' => ['بواجي']],
            ['km' => 30000, 'service' => 'تغيير فلتر الوقود', 'products' => ['فلتر بنزين']],
            ['km' => 40000, 'service' => 'تغيير سير التايمنج', 'products' => ['سير تايمنج']],
            ['km' => 60000, 'service' => 'تغيير سائل الفرامل', 'products' => ['زيت فرامل']],
            ['km' => 80000, 'service' => 'تغيير سائل ناقل الحركة', 'products' => ['زيت قير']],
        ];

        $nextService = collect($schedule)->first(fn($s) => $s['km'] > ($car->mileage ?? 0));

        return view('cars.maintenance', compact('car', 'schedule', 'nextService'));
    }
}
